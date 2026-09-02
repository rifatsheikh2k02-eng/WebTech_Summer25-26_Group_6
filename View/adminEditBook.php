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

if (!isset($_GET["book_id"])) {
    header("Location: adminCustomizeBooks.php");
    exit;
}

$bookId = $_GET["book_id"];

$sql = "SELECT * FROM books WHERE book_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $bookId);
$stmt->execute();

$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    header("Location: adminCustomizeBooks.php");
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

    <script src="../JS/adminEditBook.js"></script>

    <title>Edit Book</title>

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
        action="../Controller/adminEditBookValidation.php"
        method="POST"
        enctype="multipart/form-data"
        onsubmit="return validateEditBook()"
    >

        <fieldset>

            <legend>Edit Book</legend>

            <table>

                <tr>
                    <td>
                        <label for="bookId">Book ID:</label>
                    </td>

                    <td>
                        <input
                            type="text"
                            id="bookId"
                            name="bookId"
                            value="<?php echo htmlspecialchars($book["book_id"]); ?>"
                            readonly
                        >
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="title">Title:</label>
                    </td>

                    <td>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="<?php echo htmlspecialchars($book["title"]); ?>"
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
                            value="<?php echo htmlspecialchars($book["author"]); ?>"
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
                            value="<?php echo htmlspecialchars($book["category"]); ?>"
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
                            value="<?php echo htmlspecialchars($book["isbn"]); ?>"
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
                            id="price"
                            name="price"
                            value="<?php echo htmlspecialchars($book["price"]); ?>"
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
                            id="quantity"
                            name="quantity"
                            value="<?php echo htmlspecialchars($book["quantity"]); ?>"
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
                        ><?php echo htmlspecialchars($book["description"]); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label>Current Image:</label>
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
                </tr>

                <tr>
                    <td>
                        <label for="image">New Image:</label>
                    </td>

                    <td>

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                        >

                        <br>

                        <small>
                            Leave empty to keep the current image.
                        </small>

                    </td>
                </tr>

            </table>

            <br>

            <input
                type="submit"
                id="update"
                name="update"
                value="Update"
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