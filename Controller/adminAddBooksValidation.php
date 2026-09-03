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

    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $isbn = trim($_POST["isbn"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $quantity = trim($_POST["quantity"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if (
        empty($title) ||
        empty($author) ||
        empty($category) ||
        empty($isbn) ||
        empty($price) ||
        empty($quantity)
    ) {

        $_SESSION["admin_message"] =
            "All required fields are required.";

        header("Location: ../View/adminAddBooks.php");

        exit;
    }


    if (!is_numeric($price) || $price < 0) {

        $_SESSION["admin_message"] =
            "Invalid price.";

        header("Location: ../View/adminAddBooks.php");

        exit;
    }


    if (!is_numeric($quantity) || $quantity < 0) {

        $_SESSION["admin_message"] =
            "Invalid quantity.";

        header("Location: ../View/adminAddBooks.php");

        exit;
    }


    $sql = "SELECT isbn
            FROM books
            WHERE isbn = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $isbn);

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows > 0) {

        $_SESSION["admin_message"] =
            "ISBN already exists.";

        header("Location: ../View/adminAddBooks.php");

        exit;
    }


    if (
        !isset($_FILES["image"]) ||
        $_FILES["image"]["error"] != 0
    ) {

        $_SESSION["admin_message"] =
            "Please select an image.";

        header("Location: ../View/adminAddBooks.php");

        exit;
    }


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


    if (!in_array(
        $imageExtension,
        $allowedExtensions
    )) {

        $_SESSION["admin_message"] =
            "Only JPG, JPEG, PNG, GIF and WEBP images are allowed.";

        header("Location: ../View/adminAddBooks.php");

        exit;
    }


    if ($imageSize > 5 * 1024 * 1024) {

        $_SESSION["admin_message"] =
            "Image size must be less than 5 MB.";

        header("Location: ../View/adminAddBooks.php");

        exit;
    }


    $imageName =
        uniqid("book_", true)
        . "."
        . $imageExtension;


    $imageDestination =
        "../Uploads/"
        . $imageName;


    if (!move_uploaded_file(
        $imageTmp,
        $imageDestination
    )) {

        $_SESSION["admin_message"] =
            "Failed to upload image.";

        header("Location: ../View/adminAddBooks.php");

        exit;
    }


    $sql = "SELECT book_id
            FROM books
            WHERE book_id LIKE 'BO-%'
            ORDER BY CAST(SUBSTRING(book_id, 4) AS UNSIGNED) DESC
            LIMIT 1";

    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        $lastId = $row["book_id"];

        $lastNumber = (int) substr($lastId, 3);

        $newNumber = $lastNumber + 1;

    } else {

        $newNumber = 1001;

    }


    $bookId = "BO-" . $newNumber;


    $sql = "INSERT INTO books
            (
                book_id,
                title,
                author,
                category,
                isbn,
                price,
                quantity,
                description,
                image
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "sssssddss",
        $bookId,
        $title,
        $author,
        $category,
        $isbn,
        $price,
        $quantity,
        $description,
        $imageName
    );


    if ($stmt->execute()) {

        $_SESSION["admin_message"] =
            "Book added successfully. Book ID: " . $bookId;

    } else {

        if (file_exists($imageDestination)) {
            unlink($imageDestination);
        }

        $_SESSION["admin_message"] =
            "Book could not be added.";
    }


    header("Location: ../View/adminCustomizeBooks.php");

    exit;
}

?>