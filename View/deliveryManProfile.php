<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {

    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "Delivery Man") {

    header("Location: login.php");
    exit;
}

require_once "../Model/db.php";

$userId = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $userId);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en-US">

<head>

    <meta charset="UTF-8">

    <link rel="stylesheet" href="../Design/customer.css">

    <title>Delivery Man Profile</title>

</head>

<body>

<div class="topnav">

    <h3>BoiGhor</h3>

    <a href="deliveryManBrowse.php">
        Orders
    </a>

    <a href="deliveryManProfile.php">
        Profile
    </a>

    <a href="../Controller/logOut.php">
        Logout
    </a>

</div>


<div class="container">

    <?php

    if (isset($_SESSION["profile_message"])) {

        echo "<p>"
            . htmlspecialchars($_SESSION["profile_message"])
            . "</p>";

        unset($_SESSION["profile_message"]);
    }

    ?>


    <form
        action="../Controller/deliveryManProfileValidation.php"
        method="POST"
    >

        <fieldset>

            <legend>Delivery Man Profile</legend>

            <table>

                <tr>

                    <td>

                        <label for="userName">
                            Full name:
                        </label>

                    </td>

                    <td>

                        <input
                            type="text"
                            id="userName"
                            name="userName"
                            value="<?php echo htmlspecialchars($user["username"]); ?>"
                            required
                        >

                    </td>

                </tr>


                <tr>

                    <td>

                        <label for="userEmail">
                            Email:
                        </label>

                    </td>

                    <td>

                        <input
                            type="email"
                            id="userEmail"
                            name="userEmail"
                            value="<?php echo htmlspecialchars($user["email"]); ?>"
                            required
                        >

                    </td>

                </tr>


                <tr>

                    <td>

                        <label for="userPhoneNumber">
                            Mobile:
                        </label>

                    </td>

                    <td>

                        <input
                            type="tel"
                            id="userPhoneNumber"
                            name="userPhoneNumber"
                            value="<?php echo htmlspecialchars($user["phone"]); ?>"
                            required
                        >

                    </td>

                </tr>


                <tr>

                    <td>

                        <label for="userAddress">
                            Address:
                        </label>

                    </td>

                    <td>

                        <textarea
                            id="userAddress"
                            name="userAddress"
                            rows="5"
                            cols="20"
                            style="resize:none"
                            required
                        ><?php echo htmlspecialchars($user["address"]); ?></textarea>

                    </td>

                </tr>


                <tr>

                    <td>

                        <label for="password">
                            New Password:
                        </label>

                    </td>

                    <td>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Leave empty to keep old password"
                        >

                    </td>

                </tr>


                <tr>

                    <td>

                        <label for="confirmPass">
                            Confirm Password:
                        </label>

                    </td>

                    <td>

                        <input
                            type="password"
                            id="confirmPass"
                            name="confirmPass"
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
                            onclick="window.location.href='deliveryManBrowse.php'"
                        >
                            Back
                        </button>

        </fieldset>

    </form>

</div>


<div class="footer">

    <p>
        &copy; 2026 BoiGhor.
        All Rights Reserved (Sudortion).
    </p>

</div>

</body>

</html>