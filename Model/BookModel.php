<?php

class BookModel
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllBooks(): array
    {
        $statement = $this->connection->query(
            'SELECT id, title, author, category, price, stock FROM books ORDER BY id DESC'
        );
        return $statement->fetchAll();
    }

    public function getBookById(int $id): array|false
    {
        $statement = $this->connection->prepare(
            'SELECT id, title, author, category, price, stock FROM books WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    public function createBook(string $title, string $author, string $category, float $price, int $stock): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO books (title, author, category, price, stock)
             VALUES (:title, :author, :category, :price, :stock)'
        );
        return $statement->execute([
            'title' => $title,
            'author' => $author,
            'category' => $category,
            'price' => $price,
            'stock' => $stock
        ]);
    }

    public function updateBook(int $id, string $title, string $author, string $category, float $price, int $stock): bool
    {
        $statement = $this->connection->prepare(
            'UPDATE books SET title = :title, author = :author, category = :category, price = :price, stock = :stock WHERE id = :id'
        );
        return $statement->execute([
            'title' => $title,
            'author' => $author,
            'category' => $category,
            'price' => $price,
            'stock' => $stock,
            'id' => $id
        ]);
    }

    public function deleteBook(int $id): bool
    {
        $statement = $this->connection->prepare('DELETE FROM books WHERE id = :id');
        return $statement->execute(['id' => $id]);
    }

    public function getTotalBooks(): int
    {
        $statement = $this->connection->query('SELECT COUNT(*) AS total FROM books');
        $row = $statement->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function getTotalStockValue(): float
    {
        $statement = $this->connection->query('SELECT COALESCE(SUM(price * stock), 0) AS total FROM books');
        $row = $statement->fetch();
        return (float) ($row['total'] ?? 0);
    }
}