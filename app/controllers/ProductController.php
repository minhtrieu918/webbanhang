<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/OrderModel.php';

class ProductController
{
    private PDO $db;
    private ProductModel $productModel;
    private CategoryModel $categoryModel;
    private OrderModel $orderModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->orderModel = new OrderModel($this->db);
    }

    public function index(): void
    {
        $products = $this->productModel->getProducts();
        require __DIR__ . '/../views/product/list.php';
    }

    public function list(): void
    {
        $this->index();
    }

    public function show(int $id): void
    {
        $product = $this->findProduct($id);
        require __DIR__ . '/../views/product/show.php';
    }

    public function add(): void
    {
        $categories = $this->categoryModel->getCategories();
        $errors = [];
        $old = [];
        require __DIR__ . '/../views/product/add.php';
    }

    public function save(): void
    {
        require_post();
        verify_csrf();

        $old = $this->productInput();
        $categories = $this->categoryModel->getCategories();
        $errors = [];

        try {
            $image = $this->uploadImage($_FILES['image'] ?? null);
            $result = $this->productModel->addProduct(
                $old['name'], $old['description'], $old['price'], $old['category_id'], $image
            );
            if (is_array($result)) {
                $errors = $result;
                $this->deleteImage($image);
            } else {
                flash('success', 'Đã thêm sản phẩm.');
                redirect('Product');
            }
        } catch (RuntimeException $exception) {
            $errors['image'] = $exception->getMessage();
        }

        require __DIR__ . '/../views/product/add.php';
    }

    public function edit(int $id): void
    {
        $product = $this->findProduct($id);
        $categories = $this->categoryModel->getCategories();
        $errors = [];
        require __DIR__ . '/../views/product/edit.php';
    }

    public function update(): void
    {
        require_post();
        verify_csrf();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            exit('Mã sản phẩm không hợp lệ.');
        }

        $product = $this->findProduct($id);
        $oldImage = $product->image ?: null;
        $newImage = null;
        $data = $this->productInput();

        try {
            $newImage = $this->uploadImage($_FILES['image'] ?? null);
            $image = $newImage ?: $oldImage;
            $result = $this->productModel->updateProduct(
                $id, $data['name'], $data['description'], $data['price'], $data['category_id'], $image
            );

            if (is_array($result)) {
                $errors = $result;
                $this->deleteImage($newImage);
                foreach ($data as $key => $value) {
                    $product->{$key} = $value;
                }
                $categories = $this->categoryModel->getCategories();
                require __DIR__ . '/../views/product/edit.php';
                return;
            }

            if ($newImage) {
                $this->deleteImage($oldImage);
            }
            flash('success', 'Đã cập nhật sản phẩm.');
            redirect('Product');
        } catch (RuntimeException $exception) {
            $errors = ['image' => $exception->getMessage()];
            foreach ($data as $key => $value) {
                $product->{$key} = $value;
            }
            $categories = $this->categoryModel->getCategories();
            require __DIR__ . '/../views/product/edit.php';
        }
    }

    public function delete(int $id): never
    {
        require_post();
        verify_csrf();
        $product = $this->findProduct($id);

        try {
            $this->productModel->deleteProduct($id);
            $this->deleteImage($product->image ?: null);
            unset($_SESSION['cart'][$id]);
            flash('success', 'Đã xóa sản phẩm.');
        } catch (PDOException $exception) {
            flash('danger', 'Không thể xóa sản phẩm do dữ liệu đang được sử dụng.');
        }
        redirect('Product');
    }

    public function addToCart(int $id): never
    {
        require_post();
        verify_csrf();
        $this->findProduct($id);
        $_SESSION['cart'][$id] = min(99, ((int) ($_SESSION['cart'][$id] ?? 0)) + 1);
        flash('success', 'Đã thêm sản phẩm vào giỏ hàng.');
        redirect('Product/cart');
    }

    public function cart(): void
    {
        $items = $this->getCartItems();
        require __DIR__ . '/../views/product/cart.php';
    }

    public function updateCart(): never
    {
        require_post();
        verify_csrf();
        $quantities = $_POST['quantity'] ?? [];

        if (is_array($quantities)) {
            foreach ($quantities as $id => $quantity) {
                $id = filter_var($id, FILTER_VALIDATE_INT);
                $quantity = filter_var($quantity, FILTER_VALIDATE_INT);
                if (!$id) {
                    continue;
                }
                if (!$quantity || $quantity < 1) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id] = min(99, $quantity);
                }
            }
        }

        flash('success', 'Đã cập nhật giỏ hàng.');
        redirect('Product/cart');
    }

    public function removeFromCart(int $id): never
    {
        require_post();
        verify_csrf();
        unset($_SESSION['cart'][$id]);
        flash('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
        redirect('Product/cart');
    }

    public function checkout(): void
    {
        $items = $this->getCartItems();
        if ($items === []) {
            flash('warning', 'Giỏ hàng đang trống.');
            redirect('Product/cart');
        }
        $errors = [];
        $old = [];
        require __DIR__ . '/../views/product/checkout.php';
    }

    public function processCheckout(): void
    {
        require_post();
        verify_csrf();

        $items = $this->getCartItems();
        if ($items === []) {
            flash('warning', 'Giỏ hàng đang trống.');
            redirect('Product/cart');
        }

        $old = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'address' => trim((string) ($_POST['address'] ?? '')),
        ];
        $errors = [];
        $nameLength = function_exists('mb_strlen') ? mb_strlen($old['name']) : strlen($old['name']);
        if ($nameLength < 2 || $nameLength > 100) {
            $errors['name'] = 'Họ tên phải có từ 2 đến 100 ký tự.';
        }
        if (!preg_match('/^[0-9+ .()-]{8,20}$/', $old['phone'])) {
            $errors['phone'] = 'Số điện thoại không hợp lệ.';
        }
        if ($old['address'] === '' || strlen($old['address']) > 255) {
            $errors['address'] = 'Địa chỉ không được để trống và tối đa 255 ký tự.';
        }

        if ($errors !== []) {
            require __DIR__ . '/../views/product/checkout.php';
            return;
        }

        $orderId = $this->orderModel->createOrder($old['name'], $old['phone'], $old['address'], $items);
        unset($_SESSION['cart']);
        $_SESSION['last_order_id'] = $orderId;
        redirect('Product/orderConfirmation');
    }

    public function orderConfirmation(): void
    {
        $orderId = (int) ($_SESSION['last_order_id'] ?? 0);
        if ($orderId <= 0) {
            redirect('Product');
        }
        unset($_SESSION['last_order_id']);
        require __DIR__ . '/../views/product/orderConfirmation.php';
    }

    private function findProduct(int $id): object
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            http_response_code(404);
            exit('Không tìm thấy sản phẩm.');
        }
        return $product;
    }

    private function productInput(): array
    {
        return [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'price' => $_POST['price'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
        ];
    }

    private function uploadImage(?array $file): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Tải ảnh không thành công.');
        }
        if (($file['size'] ?? 0) > 10 * 1024 * 1024) {
            throw new RuntimeException('Ảnh không được vượt quá 10 MB.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
        if (!isset($extensions[$mime]) || @getimagesize($file['tmp_name']) === false) {
            throw new RuntimeException('Chỉ chấp nhận ảnh JPG, PNG hoặc GIF.');
        }

        $directory = dirname(__DIR__, 2) . '/uploads';
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Không thể tạo thư mục lưu ảnh.');
        }
        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
            throw new RuntimeException('Không thể lưu ảnh đã tải lên.');
        }
        return 'uploads/' . $filename;
    }

    private function deleteImage(?string $path): void
    {
        if (!$path || !str_starts_with($path, 'uploads/')) {
            return;
        }
        $file = dirname(__DIR__, 2) . '/' . $path;
        if (is_file($file)) {
            @unlink($file);
        }
    }

    private function getCartItems(): array
    {
        $cart = $_SESSION['cart'] ?? [];
        if (!is_array($cart) || $cart === []) {
            return [];
        }

        $products = $this->productModel->getProductsByIds(array_keys($cart));
        $items = [];
        foreach ($cart as $id => $quantity) {
            $id = (int) $id;
            if (!isset($products[$id])) {
                unset($_SESSION['cart'][$id]);
                continue;
            }
            $product = $products[$id];
            $product->quantity = max(1, min(99, (int) $quantity));
            $product->subtotal = (float) $product->price * $product->quantity;
            $items[] = $product;
        }
        return $items;
    }
}

