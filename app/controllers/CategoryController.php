<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController
{
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($db);
    }

    public function index(): void
    {
        $categories = $this->categoryModel->getCategories();
        require __DIR__ . '/../views/category/list.php';
    }

    public function list(): void
    {
        $this->index();
    }

    public function add(): void
    {
        $errors = [];
        $old = [];
        require __DIR__ . '/../views/category/add.php';
    }

    public function save(): void
    {
        require_post();
        verify_csrf();
        $old = $this->input();
        $result = $this->categoryModel->addCategory($old['name'], $old['description']);
        if (is_array($result)) {
            $errors = $result;
            require __DIR__ . '/../views/category/add.php';
            return;
        }
        flash('success', 'Đã thêm danh mục.');
        redirect('Category');
    }

    public function edit(int $id): void
    {
        $category = $this->findCategory($id);
        $errors = [];
        require __DIR__ . '/../views/category/edit.php';
    }

    public function update(): void
    {
        require_post();
        verify_csrf();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            exit('Mã danh mục không hợp lệ.');
        }
        $category = $this->findCategory($id);
        $data = $this->input();
        $result = $this->categoryModel->updateCategory($id, $data['name'], $data['description']);
        if (is_array($result)) {
            $errors = $result;
            $category->name = $data['name'];
            $category->description = $data['description'];
            require __DIR__ . '/../views/category/edit.php';
            return;
        }
        flash('success', 'Đã cập nhật danh mục.');
        redirect('Category');
    }

    public function delete(int $id): never
    {
        require_post();
        verify_csrf();
        $this->findCategory($id);
        try {
            $this->categoryModel->deleteCategory($id);
            flash('success', 'Đã xóa danh mục.');
        } catch (PDOException $exception) {
            flash('danger', 'Không thể xóa danh mục đang có sản phẩm.');
        }
        redirect('Category');
    }

    private function findCategory(int $id): object
    {
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            http_response_code(404);
            exit('Không tìm thấy danh mục.');
        }
        return $category;
    }

    private function input(): array
    {
        return [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
        ];
    }
}

