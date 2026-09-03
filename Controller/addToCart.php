<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: ../View/login.php");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Customer") {
    header("Location: ../View/login.php");
    exit;
}

require_once "../Model/db.php";

if (!isset($_POST["book_id"])) {
    header("Location: ../View/customerBrowse.php");
    exit;
}

$book_id = $_POST["book_id"];
$customer_id = $_SESSION["user_id"];

$sql = "SELECT * FROM books WHERE book_id = ? AND quantity > 0";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $book_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    $_SESSION["cart_message"] = "Book is not available.";

    header("Location: ../View/customerBrowse.php");
    exit;
}

$book = mysqli_fetch_assoc($result);

$book_name = $book["title"];
$price = $book["price"];
$available_quantity = $book["quantity"];

$sql = "SELECT * FROM cart 
        WHERE customer_id = ? AND book_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $customer_id,
    $book_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    $cart = mysqli_fetch_assoc($result);

    $new_quantity = $cart["quantity"] + 1;

    if ($new_quantity > $available_quantity) {

        $_SESSION["cart_message"] = "Not enough stock available for " . $book_name . ".";

        header("Location: ../View/customerBrowse.php");
        exit;
    }

    $subtotal = $price * $new_quantity;

    $sql = "UPDATE cart
            SET quantity = ?, subtotal = ?
            WHERE cart_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ids",
        $new_quantity,
        $subtotal,
        $cart["cart_id"]
    );

    mysqli_stmt_execute($stmt);

    $_SESSION["cart_message"] = $book_name . " added to cart successfully.";

} else {

    $sql = "SELECT cart_id
            FROM cart
            ORDER BY cart_id DESC
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        $last_cart = mysqli_fetch_assoc($result);

        $last_cart_id = $last_cart["cart_id"];

        $number = intval(substr($last_cart_id, 4));

        $new_number = $number + 1;

    } else {

        $new_number = 1001;
    }

    $cart_id = "CAR-" . $new_number;

    $quantity = 1;

    $subtotal = $price * $quantity;

    $sql = "INSERT INTO cart
            (cart_id, customer_id, book_id, book_name, price, quantity, subtotal)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssdid",
        $cart_id,
        $customer_id,
        $book_id,
        $book_name,
        $price,
        $quantity,
        $subtotal
    );

    mysqli_stmt_execute($stmt);

    $_SESSION["cart_message"] = $book_name . " added to cart successfully.";
}

header("Location: ../View/customerBrowse.php");
exit;

?>