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

require_once "../Model/db.php";

$customer_id = $_SESSION["user_id"];

$sql = "SELECT cart_id, book_name, price, quantity, subtotal
        FROM cart
        WHERE customer_id = ?
        ORDER BY updated_at DESC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $customer_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$total = 0;

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Design/customer.css">
    <title>My Cart</title>
</head>

<body>

    <div class="topnav">

        <h3>BoiGhor</h3>

        <a href="customerBrowse.php">Browse</a>
        <a href="customerMyOrder.php">My orders</a>
        <a href="customerProfile.php">Profile</a>
        <a href="customerCart.php" id="cart">Cart</a>

        <a href="customerProfile.php">
            <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </a>

    </div>

    <div class="main">

        <h2>My Cart</h2>

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <table class="cart-table">

                <tr>
                    <th>Book Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>

                <?php while ($cart = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($cart["book_name"]); ?>
                        </td>

                        <td>
                            ৳<?php echo number_format($cart["price"], 2); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($cart["quantity"]); ?>
                        </td>

                        <td>
                            ৳<?php echo number_format($cart["subtotal"], 2); ?>
                        </td>

                        <td>

                            <form action="../Controller/removeFromCart.php" method="POST">

                                <input
                                    type="hidden"
                                    name="cart_id"
                                    value="<?php echo htmlspecialchars($cart["cart_id"]); ?>"
                                >

                                <button
                                    type="submit"
                                    class="remove-button"
                                    onclick="return confirm('Are you sure you want to remove this book?');"
                                >
                                    Remove
                                </button>

                            </form>

                        </td>

                    </tr>

                    <?php $total += $cart["subtotal"]; ?>

                <?php } ?>

            </table>

            <div class="cart-total">

    <h3>
        Total Bill:
        ৳<?php echo number_format($total, 2); ?>
    </h3>

    <button type="button" onclick="window.location.href='customerPayment.php'">
        Proceed to Payment
    </button>

</div>

        <?php } else { ?>

            <div class="empty-cart">
                <p>Your cart is empty.</p>
            </div>

        <?php } ?>

    </div>

    <div class="footer">

        <p>
            &copy; 2026 BoiGhor. All Rights Reserved (sudortion).
        </p>

    </div>

</body>

</html>