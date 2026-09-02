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

$sql = "SELECT * FROM books WHERE quantity > 0 ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$cart_message = null;

if (isset($_SESSION["cart_message"])) {
    $cart_message = $_SESSION["cart_message"];
    unset($_SESSION["cart_message"]);
}

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../Design/customer.css">
    <title>Customer Browse</title>
</head>

<body>

<?php if ($cart_message !== null) { ?>

<script>
    alert("<?php echo htmlspecialchars($cart_message); ?>");
</script>

<?php } ?>

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

    <h2>Available Books</h2>

    <div class="book-container">

        <?php

        if (mysqli_num_rows($result) > 0) {

            while ($book = mysqli_fetch_assoc($result)) {

        ?>

        <div class="book-card">

            <?php if (!empty($book["image"])) { ?>

                <img
                    src="../uploads/<?php echo htmlspecialchars($book["image"]); ?>"
                    alt="<?php echo htmlspecialchars($book["title"]); ?>"
                >

            <?php } else { ?>

                <div class="no-image">
                    No Image
                </div>

            <?php } ?>

            <h3>
                <?php echo htmlspecialchars($book["title"]); ?>
            </h3>

            <p>
                <strong>Author:</strong>
                <?php echo htmlspecialchars($book["author"]); ?>
            </p>

            <p>
                <strong>Category:</strong>
                <?php echo htmlspecialchars($book["category"]); ?>
            </p>

            <p>
                <strong>ISBN:</strong>
                <?php echo htmlspecialchars($book["isbn"]); ?>
            </p>

            <p>
                <strong>Price:</strong>
                ৳<?php echo htmlspecialchars($book["price"]); ?>
            </p>

            <p>
                <strong>Available:</strong>
                <?php echo htmlspecialchars($book["quantity"]); ?>
            </p>

            <p>
                <?php echo htmlspecialchars($book["description"]); ?>
            </p>

            <form action="../Controller/addToCart.php" method="POST">

                <input
                    type="hidden"
                    name="book_id"
                    value="<?php echo htmlspecialchars($book["book_id"]); ?>"
                >

                <button type="submit">
                    Add to Cart
                </button>

            </form>

        </div>

        <?php

            }

        } else {

            echo "<p>No books available.</p>";

        }

        ?>

    </div>

</div>

<div class="footer">

    <p>&copy; 2026 BoiGhor. All Rights Reserved (sudortion).</p>

</div>

</body>

</html>