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


if (!isset($_POST["order_id"])) {

    header("Location: ../View/adminDashboard.php");
    exit;
}


$orderId = $_POST["order_id"];


$sql = "UPDATE orders

        SET order_status = 'Deliver'

        WHERE order_id = ?

        AND order_status = 'Confirmed'";


$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $orderId);


if ($stmt->execute()) {

    $_SESSION["admin_message"] =

        "Order " . $orderId . " has been sent for delivery successfully.";

} else {

    $_SESSION["admin_message"] =

        "Failed to update order status.";

}


header("Location: ../View/adminDashboard.php");

exit;

?>