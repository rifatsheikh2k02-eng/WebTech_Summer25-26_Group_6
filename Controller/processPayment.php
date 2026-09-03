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

include "../Model/db.php";

$userId = $_SESSION["user_id"];

if (!isset($_POST["pay"])) {

    header("Location: ../View/customerCart.php");
    exit;
}

$cardName = trim($_POST["cardName"]);
$cardNumber = trim($_POST["cardNumber"]);
$expiryDate = trim($_POST["expiryDate"]);
$cvc = trim($_POST["cvc"]);

if (
    empty($cardName) ||
    empty($cardNumber) ||
    empty($expiryDate) ||
    empty($cvc)
) {

    $_SESSION["payment_message"] = "Please fill all card details.";

    header("Location: ../View/customerPayment.php");
    exit;
}

$cardNumber = str_replace(" ", "", $cardNumber);

if (!preg_match("/^[0-9]{16}$/", $cardNumber)) {

    $_SESSION["payment_message"] = "Card number must contain 16 digits.";

    header("Location: ../View/customerPayment.php");
    exit;
}

if (!preg_match("/^(0[1-9]|1[0-2])\/[0-9]{2}$/", $expiryDate)) {

    $_SESSION["payment_message"] = "Invalid expiry date. Use MM/YY.";

    header("Location: ../View/customerPayment.php");
    exit;
}

if (!preg_match("/^[0-9]{3}$/", $cvc)) {

    $_SESSION["payment_message"] = "CVC must contain 3 digits.";

    header("Location: ../View/customerPayment.php");
    exit;
}

$sql = "SELECT book_id, book_name, price, quantity, subtotal
        FROM cart
        WHERE customer_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $userId);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

    $_SESSION["payment_message"] = "Your cart is empty.";

    header("Location: ../View/customerCart.php");
    exit;
}

$items = [];

$total = 0;

while ($cart = $result->fetch_assoc()) {

    $items[] = $cart["book_name"] . " x" . $cart["quantity"];

    $total += $cart["subtotal"];
}

$itemList = implode(", ", $items);

$sql = "SELECT order_id
        FROM orders
        ORDER BY order_id DESC
        LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $lastOrder = $result->fetch_assoc();

    $lastOrderId = $lastOrder["order_id"];

    $number = intval(substr($lastOrderId, 4));

    $newNumber = $number + 1;

} else {

    $newNumber = 1001;
}

$orderId = "ORD-" . $newNumber;

$orderStatus = "Confirmed";

$sql = "INSERT INTO orders
        (order_id, customer_id, items, total_amount, order_status)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssds",
    $orderId,
    $userId,
    $itemList,
    $total,
    $orderStatus
);

if ($stmt->execute()) {

    $sql = "DELETE FROM cart
            WHERE customer_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $userId);

    $stmt->execute();

    $_SESSION["order_message"] = "Payment successful! Your order ID is " . $orderId;

    header("Location: ../View/customerMyOrder.php");
    exit;

} else {

    $_SESSION["payment_message"] = "Payment failed. Please try again.";

    header("Location: ../View/customerPayment.php");
    exit;
}

?>