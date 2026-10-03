<?php
declare(strict_types=1);

class CategoryModel
{
    public function __construct(private PDO $conn)
    {
    }

    public function getCategories(): array
    {
        return $this->conn->query(
            'SELECT c.id, c.name, c.description, COUNT(p.id) AS product_count
             FROM category c
             LEFT JOIN product p ON p.category_id = c.id
             GROUP BY c.id, c.name, c.description
             ORDER BY c.name'
        )->fetchAll();
    }

    public function getCategoryById(int $id): object|false
    {
        $statement = $this->conn->prepare('SELECT * FROM category WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    public function addCategory(string $name, string $description): array|bool
    {
        $errors = $this->validate($name);
        if ($errors !== []) {
            return $errors;
        }

        $statement = $this->conn->prepare(
            'INSERT INTO category (name, description) VALUES (:name, :description)'
        );
        return $statement->execute(['name' => trim($name), 'description' => trim($description)]);
    }

    public function updateCategory(int $id, string $name, string $description): array|bool
    {
        $errors = $this->validate($name);
        if ($errors !== []) {
            return $errors;
        }

        $statement = $this->conn->prepare(
            'UPDATE category SET name = :name, description = :description WHERE id = :id'
        );
        return $statement->execute(['id' => $id, 'name' => trim($name), 'description' => trim($description)]);
    }

    public function deleteCategory(int $id): bool
    {
        $statement = $this->conn->prepare('DELETE FROM category WHERE id = :id');
        return $statement->execute(['id' => $id]);
    }

    private function validate(string $name): array
    {
        $length = function_exists('mb_strlen') ? mb_strlen(trim($name)) : strlen(trim($name));
        if ($length < 2 || $length > 100) {
            return ['name' => 'Tên danh mục phải có từ 2 đến 100 ký tự.'];
        }
        return [];
    }
}

