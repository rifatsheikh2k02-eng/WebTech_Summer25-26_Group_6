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


if (!isset($_POST["book_id"])) {

    header("Location: ../View/adminCustomizeBooks.php");

    exit;
}


$bookId = $_POST["book_id"];


/* Check whether the book is in any customer's cart */

$sql = "SELECT * FROM cart
        WHERE book_id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $bookId);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows > 0) {

    $_SESSION["admin_message"] =
        "This book is already in a customer cart. You cannot delete it.";

    header("Location: ../View/adminCustomizeBooks.php");

    exit;
}


/* Delete book */

$sql = "DELETE FROM books
        WHERE book_id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $bookId);


if ($stmt->execute()) {

    $_SESSION["admin_message"] =
        "Book " . $bookId . " has been deleted successfully.";

} else {

    $_SESSION["admin_message"] =
        "Failed to delete book.";

}


header("Location: ../View/adminCustomizeBooks.php");

exit;

?>