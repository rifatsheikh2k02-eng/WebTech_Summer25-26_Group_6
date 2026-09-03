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

$sql = "SELECT book_name, price, quantity, subtotal
        FROM cart
        WHERE customer_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $userId);

$stmt->execute();

$result = $stmt->get_result();

$total = 0;

?>

<!DOCTYPE html>

<html lang="en-US">

<head>

<meta charset="UTF-8">

<link rel="stylesheet" href="../Design/customer.css">
<script src="../JS/payment.js"></script>

<title>Payment</title>

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


<?php

if (isset($_SESSION["payment_message"])) {

    $paymentMessage = $_SESSION["payment_message"];

    unset($_SESSION["payment_message"]);

?>

<script>
    alert("<?php echo htmlspecialchars($paymentMessage); ?>");
</script>

<?php

}

?>


<div class="container">

    <?php if ($result->num_rows > 0) { ?>

        <form
            action="../Controller/processPayment.php"
            method="POST"
            onsubmit="return validatePayment()"
        >

            <fieldset>

                <legend>Card Payment</legend>

                <table>

                    <tr>

                        <td>

                            <label for="cardName">
                                Card Holder Name:
                            </label>

                        </td>

                        <td>

                            <input
                                type="text"
                                id="cardName"
                                name="cardName"
                                placeholder="Enter card holder name"
                                required
                            >

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <label for="cardNumber">
                                Card Number:
                            </label>

                        </td>

                        <td>

                            <input
                                type="text"
                                id="cardNumber"
                                name="cardNumber"
                                placeholder="1234 5678 9012 3456"
                                maxlength="19"
                                required
                            >

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <label for="expiryDate">
                                Expiry Date:
                            </label>

                        </td>

                        <td>

                            <input
                                type="text"
                                id="expiryDate"
                                name="expiryDate"
                                placeholder="MM/YY"
                                maxlength="5"
                                required
                            >

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <label for="cvc">
                                CVC:
                            </label>

                        </td>

                        <td>

                            <input
                                type="password"
                                id="cvc"
                                name="cvc"
                                placeholder="123"
                                maxlength="3"
                                required
                            >

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <label>
                                Order Items:
                            </label>

                        </td>

                        <td>

                            <?php

                            while ($cart = $result->fetch_assoc()) {

                                echo htmlspecialchars($cart["book_name"]);
                                echo " x";
                                echo htmlspecialchars($cart["quantity"]);
                                echo " - ৳";
                                echo number_format($cart["subtotal"], 2);
                                echo "<br>";

                                $total += $cart["subtotal"];

                            }

                            ?>

                        </td>

                    </tr>


                    <tr>

                        <td>

                            <label for="totalAmount">
                                Total Bill:
                            </label>

                        </td>

                        <td>

                            <input
                                type="text"
                                id="totalAmount"
                                value="৳<?php echo number_format($total, 2); ?>"
                                readonly
                            >

                        </td>

                    </tr>


                    <tr>

                        <td>


                        </td>

                    </tr>

                </table>
                <input
                                type="submit"
                                id="pay"
                                name="pay"
                                value="Pay Now"
                            >

                            <br>
                            <br>

                            <button
                                type="button"
                                id="back"
                                name="back"
                                onclick="window.location.href='customerCart.php'"
                            >
                                Back
                            </button>

            </fieldset>

        </form>

    <?php } else { ?>

        <fieldset>

            <legend>Payment</legend>

            <p>Cart is empty.</p>

            <button
                type="button"
                onclick="window.location.href='customerBrowse.php'"
            >
                Browse Books
            </button>

        </fieldset>

    <?php } ?>

</div>


<div class="footer">

    <p>
        &copy; 2026 BoiGhor.
        All Rights Reserved (Sudortion).
    </p>

</div>

</body>

</html>