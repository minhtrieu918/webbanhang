<?php
declare(strict_types=1);

class ProductModel
{
    public function __construct(private PDO $conn)
    {
    }

    public function getProducts(): array
    {
        $query = 'SELECT p.id, p.name, p.description, p.price, p.image, p.category_id,
                         c.name AS category_name
                  FROM product p
                  LEFT JOIN category c ON p.category_id = c.id
                  ORDER BY p.id DESC';
        return $this->conn->query($query)->fetchAll();
    }

    public function getProductById(int $id): object|false
    {
        $query = 'SELECT p.*, c.name AS category_name
                  FROM product p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.id = :id';
        $statement = $this->conn->prepare($query);
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    public function getProductsByIds(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids), fn ($id) => $id > 0)));
        if ($ids === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $statement = $this->conn->prepare(
            "SELECT p.*, c.name AS category_name FROM product p
             LEFT JOIN category c ON p.category_id = c.id
             WHERE p.id IN ($placeholders)"
        );
        $statement->execute($ids);
        $products = [];
        foreach ($statement->fetchAll() as $product) {
            $products[(int) $product->id] = $product;
        }
        return $products;
    }

    public function addProduct(
        string $name,
        string $description,
        mixed $price,
        mixed $categoryId,
        ?string $image
    ): array|bool {
        $errors = $this->validate($name, $description, $price, $categoryId);
        if ($errors !== []) {
            return $errors;
        }

        $statement = $this->conn->prepare(
            'INSERT INTO product (name, description, price, category_id, image)
             VALUES (:name, :description, :price, :category_id, :image)'
        );
        return $statement->execute([
            'name' => trim($name),
            'description' => trim($description),
            'price' => $price,
            'category_id' => (int) $categoryId,
            'image' => $image ?: null,
        ]);
    }

    public function updateProduct(
        int $id,
        string $name,
        string $description,
        mixed $price,
        mixed $categoryId,
        ?string $image
    ): array|bool {
        $errors = $this->validate($name, $description, $price, $categoryId);
        if ($errors !== []) {
            return $errors;
        }

        $statement = $this->conn->prepare(
            'UPDATE product
             SET name = :name, description = :description, price = :price,
                 category_id = :category_id, image = :image
             WHERE id = :id'
        );
        return $statement->execute([
            'id' => $id,
            'name' => trim($name),
            'description' => trim($description),
            'price' => $price,
            'category_id' => (int) $categoryId,
            'image' => $image ?: null,
        ]);
    }

    public function deleteProduct(int $id): bool
    {
        $statement = $this->conn->prepare('DELETE FROM product WHERE id = :id');
        return $statement->execute(['id' => $id]);
    }

    private function validate(string $name, string $description, mixed $price, mixed $categoryId): array
    {
        $errors = [];
        $length = function_exists('mb_strlen') ? mb_strlen(trim($name)) : strlen(trim($name));

        if ($length < 10 || $length > 100) {
            $errors['name'] = 'Tên sản phẩm phải có từ 10 đến 100 ký tự.';
        }
        if (trim($description) === '') {
            $errors['description'] = 'Mô tả không được để trống.';
        }
        if (!is_numeric($price) || (float) $price <= 0) {
            $errors['price'] = 'Giá phải là số lớn hơn 0.';
        }
        if (filter_var($categoryId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
            $errors['category_id'] = 'Danh mục không hợp lệ.';
        }
        return $errors;
    }
}

