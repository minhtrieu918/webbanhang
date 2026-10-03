<?php
declare(strict_types=1);

class OrderModel
{
    public function __construct(private PDO $conn)
    {
    }

    public function createOrder(string $name, string $phone, string $address, array $items): int
    {
        $this->conn->beginTransaction();
        try {
            $total = 0.0;
            foreach ($items as $item) {
                $total += (float) $item->price * (int) $item->quantity;
            }

            $orderStatement = $this->conn->prepare(
                'INSERT INTO orders (name, phone, address, total_amount)
                 VALUES (:name, :phone, :address, :total_amount)'
            );
            $orderStatement->execute([
                'name' => trim($name),
                'phone' => trim($phone),
                'address' => trim($address),
                'total_amount' => $total,
            ]);
            $orderId = (int) $this->conn->lastInsertId();

            $detailStatement = $this->conn->prepare(
                'INSERT INTO order_details
                    (order_id, product_id, product_name, quantity, price)
                 VALUES (:order_id, :product_id, :product_name, :quantity, :price)'
            );
            foreach ($items as $item) {
                $detailStatement->execute([
                    'order_id' => $orderId,
                    'product_id' => (int) $item->id,
                    'product_name' => $item->name,
                    'quantity' => (int) $item->quantity,
                    'price' => $item->price,
                ]);
            }

            $this->conn->commit();
            return $orderId;
        } catch (Throwable $exception) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $exception;
        }
    }
}

