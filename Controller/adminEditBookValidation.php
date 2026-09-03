<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: ../View/login.php");
    exit;
}

if ($_SESSION["role"] !== "Admin") {
    header("Location: ../View/login.php");
    exit;
}

require_once "../Model/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $bookId = trim($_POST["bookId"] ?? "");
    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $isbn = trim($_POST["isbn"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $quantity = trim($_POST["quantity"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if (
        empty($bookId) ||
        empty($title) ||
        empty($author) ||
        empty($category) ||
        empty($isbn) ||
        empty($price) ||
        empty($quantity)
    ) {

        $_SESSION["admin_message"] = "All required fields are required.";

        header(
            "Location: ../View/adminEditBook.php?book_id="
            . urlencode($bookId)
        );

        exit;
    }

    if (!is_numeric($price) || $price < 0) {

        $_SESSION["admin_message"] = "Invalid price.";

        header(
            "Location: ../View/adminEditBook.php?book_id="
            . urlencode($bookId)
        );

        exit;
    }

    if (!is_numeric($quantity) || $quantity < 0) {

        $_SESSION["admin_message"] = "Invalid quantity.";

        header(
            "Location: ../View/adminEditBook.php?book_id="
            . urlencode($bookId)
        );

        exit;
    }

    $sql = "SELECT image FROM books WHERE book_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bookId);
    $stmt->execute();

    $result = $stmt->get_result();
    $oldBook = $result->fetch_assoc();

    if (!$oldBook) {

        $_SESSION["admin_message"] = "Book not found.";

        header("Location: ../View/adminCustomizeBooks.php");

        exit;
    }

    $imageName = $oldBook["image"];

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] == 0
    ) {

        $originalImageName = $_FILES["image"]["name"];
        $imageTmp = $_FILES["image"]["tmp_name"];
        $imageSize = $_FILES["image"]["size"];

        $imageExtension = strtolower(
            pathinfo(
                $originalImageName,
                PATHINFO_EXTENSION
            )
        );

        $allowedExtensions = [
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp"
        ];

        if (!in_array($imageExtension, $allowedExtensions)) {

            $_SESSION["admin_message"] =
                "Only JPG, JPEG, PNG, GIF and WEBP images are allowed.";

            header(
                "Location: ../View/adminEditBook.php?book_id="
                . urlencode($bookId)
            );

            exit;
        }

        if ($imageSize > 5 * 1024 * 1024) {

            $_SESSION["admin_message"] =
                "Image size must be less than 5 MB.";

            header(
                "Location: ../View/adminEditBook.php?book_id="
                . urlencode($bookId)
            );

            exit;
        }

        $newImageName =
            uniqid("book_", true)
            . "."
            . $imageExtension;

        $imageDestination =
            "../Uploads/"
            . $newImageName;

        if (
            !move_uploaded_file(
                $imageTmp,
                $imageDestination
            )
        ) {

            $_SESSION["admin_message"] =
                "Failed to upload image.";

            header(
                "Location: ../View/adminEditBook.php?book_id="
                . urlencode($bookId)
            );

            exit;
        }

        if (!empty($oldBook["image"])) {

            $oldImagePath =
                "../Uploads/"
                . $oldBook["image"];

            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $imageName = $newImageName;
    }

    $sql = "UPDATE books SET
            title = ?,
            author = ?,
            category = ?,
            isbn = ?,
            price = ?,
            quantity = ?,
            description = ?,
            image = ?
            WHERE book_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssddsss",
        $title,
        $author,
        $category,
        $isbn,
        $price,
        $quantity,
        $description,
        $imageName,
        $bookId
    );

    if ($stmt->execute()) {

        $_SESSION["admin_message"] =
            "Book updated successfully.";

    } else {

        $_SESSION["admin_message"] =
            "Book update failed.";

    }

    header(
        "Location: ../View/adminEditBook.php?book_id="
        . urlencode($bookId)
    );

    exit;
}

?>