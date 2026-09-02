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
    <script src="../JS/adminAddBooks.js"></script>
    <title>Add Book</title>

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


<div class="container">

    <form
        action="../Controller/adminAddBooksValidation.php"
        method="POST"
        enctype="multipart/form-data"
        onsubmit="return validateAddBook()"
    >

        <fieldset>

            <legend>Add New Book</legend>

            <table>

                <tr>

                    <td>
                        <label for="title">Title:</label>
                    </td>

                    <td>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            required
                        >
                    </td>

                </tr>


                <tr>

                    <td>
                        <label for="author">Author:</label>
                    </td>

                    <td>
                        <input
                            type="text"
                            id="author"
                            name="author"
                            required
                        >
                    </td>

                </tr>


                <tr>

                    <td>
                        <label for="category">Category:</label>
                    </td>

                    <td>
                        <input
                            type="text"
                            id="category"
                            name="category"
                            required
                        >
                    </td>

                </tr>


                <tr>

                    <td>
                        <label for="isbn">ISBN:</label>
                    </td>

                    <td>
                        <input
                            type="text"
                            id="isbn"
                            name="isbn"
                            required
                        >
                    </td>

                </tr>


                <tr>

                    <td>
                        <label for="price">Price:</label>
                    </td>

                    <td>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="price"
                            name="price"
                            required
                        >
                    </td>

                </tr>


                <tr>

                    <td>
                        <label for="quantity">Quantity:</label>
                    </td>

                    <td>
                        <input
                            type="number"
                            min="0"
                            id="quantity"
                            name="quantity"
                            required
                        >
                    </td>

                </tr>


                <tr>

                    <td>
                        <label for="description">Description:</label>
                    </td>

                    <td>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            cols="20"
                            style="resize:none"
                        ></textarea>

                    </td>

                </tr>


                <tr>

                    <td>
                        <label for="image">Image:</label>
                    </td>

                    <td>

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            required
                        >

                    </td>

                </tr>

            </table>


            <br>


            <input
                type="submit"
                id="add"
                name="add"
                value="Add Book"
            >


            <br>
            <br>


            <button
                type="button"
                id="back"
                name="back"
                onclick="window.location.href='adminCustomizeBooks.php'"
            >
                Back
            </button>

        </fieldset>

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