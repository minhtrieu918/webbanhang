<?php
declare(strict_types=1);

class Database
{
    private string $host;
    private string $database;
    private string $username;
    private string $password;
    private string $port;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->database = getenv('DB_NAME') ?: 'my_store';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
        $this->port = getenv('DB_PORT') ?: '3306';
    }

    public function getConnection(): PDO
    {
        try {
            return new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            http_response_code(500);
            exit('Không thể kết nối cơ sở dữ liệu. Kiểm tra MySQL và cấu hình trong app/config/database.php.');
        }
    }
}

