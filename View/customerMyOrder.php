<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {

    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "Customer") {

    header("Location: login.php");
    exit;
}

include "../Model/db.php";

$userId = $_SESSION["user_id"];

$sql = "SELECT order_id, items, total_amount, order_status, order_date
        FROM orders
        WHERE customer_id = ?
        ORDER BY order_date DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $userId);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en-US">

<head>

    <meta charset="UTF-8">

    <link rel="stylesheet" href="../Design/customer.css">

    <title>My Orders</title>

</head>

<body>

    <div class="topnav">

        <h3>BoiGhor</h3>

        <a href="customerBrowse.php">Browse</a>

        <a href="customerMyOrder.php">My orders</a>

        <a href="customerProfile.php">Profile</a>

        <a href="customerCart.php" id="cart" name="cart">Cart</a>

        <a href="../Controller/logOut.php">Logout</a>

    </div>


    <div class="main">

        <?php

        if (isset($_SESSION["order_message"])) {

            echo "<p class='payment-message'>"
                . htmlspecialchars($_SESSION["order_message"])
                . "</p>";

            unset($_SESSION["order_message"]);
        }

        ?>


        <h2>My Orders</h2>


        <?php if ($result->num_rows > 0) { ?>


            <table class="order-table">

                <tr>

                    <th>Order ID</th>

                    <th>Items</th>

                    <th>Total Bill</th>

                    <th>Status</th>

                    <th>Order Date</th>

                </tr>


                <?php while ($order = $result->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php
                            echo htmlspecialchars($order["order_id"]);
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars($order["items"]);
                            ?>
                        </td>


                        <td>
                            ৳<?php
                            echo number_format(
                                $order["total_amount"],
                                2
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $order["order_status"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $order["order_date"]
                            );
                            ?>
                        </td>

                    </tr>

                <?php } ?>


            </table>


        <?php } else { ?>


            <div class="empty-orders">

                <p>You have no orders yet.</p>

                <button
                    type="button"
                    onclick="window.location.href='customerBrowse.php'"
                >
                    Browse Books
                </button>

            </div>


        <?php } ?>


    </div>


    <div class="footer">

        <p>
            &copy; 2026 BoiGhor.
            All Rights Reserved(sudortion).
        </p>

    </div>

</body>

</html>