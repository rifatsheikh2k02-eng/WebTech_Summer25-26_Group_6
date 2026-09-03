<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {

    header("Location: ../View/login.php");
    exit;
}

if ($_SESSION["role"] !== "Delivery Man") {

    header("Location: ../View/login.php");
    exit;
}

require_once "../Model/db.php";


if (!isset($_POST["order_id"])) {

    header("Location: ../View/deliveryManBrowse.php");
    exit;
}


$orderId = $_POST["order_id"];


$sql = "UPDATE orders
        SET order_status = 'Delivered'
        WHERE order_id = ?
        AND order_status = 'Deliver'";


$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $orderId);


if ($stmt->execute()) {

    $_SESSION["delivery_message"] =
        "Order " . $orderId . " has been delivered successfully.";

} else {

    $_SESSION["delivery_message"] =
        "Failed to update order status.";

}


header("Location: ../View/deliveryManBrowse.php");

exit;

?>