<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: ../View/login.php");
    exit;
}

if ($_SESSION["role"] !== "Customer") {
    header("Location: ../View/login.php");
    exit;
}

require_once "../Model/db.php";

if (!isset($_POST["cart_id"])) {
    header("Location: ../View/customerCart.php");
    exit;
}

$cart_id = $_POST["cart_id"];
$customer_id = $_SESSION["user_id"];

$sql = "DELETE FROM cart
        WHERE cart_id = ? AND customer_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $cart_id,
    $customer_id
);

mysqli_stmt_execute($stmt);

header("Location: ../View/customerCart.php");
exit;

?>