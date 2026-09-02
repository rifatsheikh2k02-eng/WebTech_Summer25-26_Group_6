<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {

    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "Admin") {

    header("Location: login.php");
    exit;
}

require_once "../Model/db.php";

$sql = "SELECT order_id, customer_id, items, total_amount, order_status, order_date
        FROM orders
        WHERE order_status = 'Confirmed'
        ORDER BY order_date DESC";

$result = mysqli_query($conn, $sql);

$admin_message = null;

if (isset($_SESSION["admin_message"])) {

    $admin_message = $_SESSION["admin_message"];

    unset($_SESSION["admin_message"]);
}

?>

<!DOCTYPE html>

<html lang="en-US">

<head>

    <meta charset="UTF-8">

    <link rel="stylesheet" href="../Design/customer.css">

    <title>Admin Dashboard</title>

</head>

<body>


<?php if ($admin_message !== null) { ?>

<script>

    alert("<?php echo htmlspecialchars($admin_message); ?>");

</script>

<?php } ?>


<div class="topnav">

    <h3>BoiGhor</h3>

    <a href="../Controller/logOut.php">Logout</a>
    <a href="adminCustomizeBooks.php">Books</a>

    <a href="adminProfile.php">

        <?php echo htmlspecialchars($_SESSION["username"]); ?>

    </a>

</div>


<div class="main">

    <h2>Confirmed Orders</h2>


    <?php if (mysqli_num_rows($result) > 0) { ?>


        <table class="order-table">

            <tr>

                <th>Order ID</th>

                <th>Customer ID</th>

                <th>Items</th>

                <th>Total Bill</th>

                <th>Order Date</th>

                <th>Status</th>

            </tr>


            <?php while ($order = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>

                        <?php

                        echo htmlspecialchars($order["order_id"]);

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars($order["customer_id"]);

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

                        echo htmlspecialchars($order["order_date"]);

                        ?>

                    </td>


                    <td>

                        <form

                            action="../Controller/adminDashboardValidation.php"

                            method="POST"

                        >

                            <input

                                type="hidden"

                                name="order_id"

                                value="<?php echo htmlspecialchars($order["order_id"]); ?>"

                            >


                            <button

                                type="submit"

                                class="delivery-button"

                            >

                                Deliver

                            </button>

                        </form>

                    </td>

                </tr>

            <?php } ?>


        </table>


    <?php } else { ?>


        <div class="empty-orders">

            <p>No confirmed orders are available.</p>

        </div>


    <?php } ?>


</div>


<div class="footer">

    <p>

        &copy; 2026 BoiGhor.

        All Rights Reserved (sudortion).

    </p>

</div>


</body>

</html>