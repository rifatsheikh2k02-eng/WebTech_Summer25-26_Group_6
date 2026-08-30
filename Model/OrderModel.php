<?php

class OrderModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getTotalRevenue(): float
    {
        $statement = $this->connection->query(
            "SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE status = 'delivered'"
        );
        $row = $statement->fetch();
        return (float) ($row['total'] ?? 0);
    }

    public function getMonthlyRevenue(): float
    {
        $statement = $this->connection->query(
            "SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders 
             WHERE status = 'delivered' 
             AND YEAR(created_at) = YEAR(CURDATE()) 
             AND MONTH(created_at) = MONTH(CURDATE())"
        );
        $row = $statement->fetch();
        return (float) ($row['total'] ?? 0);
    }

    public function getTotalOrders(): int
    {
        $statement = $this->connection->query('SELECT COUNT(*) AS total FROM orders');
        $row = $statement->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function getDeliveredOrdersCount(): int
    {
        $statement = $this->connection->prepare('SELECT COUNT(*) AS total FROM orders WHERE status = :status');
        $statement->execute(['status' => 'delivered']);
        $row = $statement->fetch();
        return (int) ($row['total'] ?? 0);
    }
}