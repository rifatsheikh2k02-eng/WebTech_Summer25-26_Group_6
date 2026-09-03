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


$sql = "SELECT *
        FROM books
        ORDER BY created_at DESC";

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

    <title>Customize Books</title>

</head>

<body>


<?php if ($admin_message !== null) { ?>

<script>

    alert("<?php echo htmlspecialchars($admin_message); ?>");

</script>

<?php } ?>


<div class="topnav">

    <h3>BoiGhor</h3>

    <a href="adminDashboard.php">Dashboard</a>

    <a href="adminCustomizeBooks.php">Books</a>

    <a href="adminProfile.php">

        <?php echo htmlspecialchars($_SESSION["username"]); ?>

    </a>

    <a href="../Controller/logOut.php">Logout</a>

</div>


<div class="main">

    <h2>Manage Books</h2>


    <?php if (mysqli_num_rows($result) > 0) { ?>


        <table class="order-table">

            <tr>

                <th>Book ID</th>

                <th>Title</th>

                <th>Author</th>

                <th>Category</th>

                <th>ISBN</th>

                <th>Price</th>

                <th>Quantity</th>

                <th>Description</th>

                <th>Image</th>

                <th>Action</th>

            </tr>


            <?php while ($book = mysqli_fetch_assoc($result)) { ?>

                <tr>


                    <td>

                        <?php

                        echo htmlspecialchars($book["book_id"]);

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars($book["title"]);

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars($book["author"]);

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars($book["category"]);

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars($book["isbn"]);

                        ?>

                    </td>


                    <td>

                        ৳<?php

                        echo number_format(

                            $book["price"],

                            2

                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars($book["quantity"]);

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars($book["description"]);

                        ?>

                    </td>


                    <td>

                       <?php if (!empty($book["image"])) { ?>

    <img
        src="../Uploads/<?php echo htmlspecialchars($book["image"]); ?>"
        alt="Book Image"
        width="100"
    >

<?php } else { ?>

    No image

<?php } ?>

                    </td>


                    <td>


                        <!-- Edit Button -->

                        <form

                            action="adminEditBook.php"

                            method="GET"

                            style="display:inline;"

                        >

                            <input

                                type="hidden"

                                name="book_id"

                                value="<?php echo htmlspecialchars($book["book_id"]); ?>"

                            >

                            <button

                                type="submit"

                                class="delivery-button"

                            >

                                Edit

                            </button>

                        </form>


                        <br>

                        <br>


                        <form

                            action="../Controller/adminCustomizeBooksValidation.php"

                            method="POST"

                            style="display:inline;"

                        >

                            <input

                                type="hidden"

                                name="book_id"

                                value="<?php echo htmlspecialchars($book["book_id"]); ?>"

                            >


                            <button

                                type="submit"

                                class="delivery-button"

                                onclick="return confirm('Are you sure you want to delete this book?');"

                            >

                                Delete

                            </button>

                        </form>


                    </td>


                </tr>

            <?php } ?>


        </table>


    <?php } else { ?>


        <div class="empty-orders">

            <p>No books are available.</p>

        </div>


    <?php } ?>


</div>

<div class="add-book">

    <form action="adminAddBooks.php" method="GET">

        <button
            type="submit"
            class="delivery-button"
        >
            Add Books
        </button>

    </form>

</div>


<div class="footer">

    <p>

        &copy; 2026 BoiGhor.

        All Rights Reserved (sudortion).

    </p>

</div>


</body>

</html>