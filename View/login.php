<?php

// session_start();

include "../Controller/loginValidation.php";

$signupMessage = "";

if (isset($_SESSION["signup_success"])) {

    $signupMessage = $_SESSION["signup_success"];

    unset($_SESSION["signup_success"]);
}

?>

<!DOCTYPE html>
<html lang="en-US">

<head>

    <meta charset="UTF-8">

    <link rel="stylesheet" href="../Design/login.css">

    <script src="../JS/login.js"></script>

    <title>Login</title>

</head>

<body>

<div class="container">

    <?php

    if ($signupMessage != "") {

        echo "<p style='color: green; font-weight: bold;'>"
            . htmlspecialchars($signupMessage)
            . "</p>";
    }

    if (!empty($message)) {

        echo "<p style='color: red; font-weight: bold;'>"
            . htmlspecialchars($message)
            . "</p>";
    }

    ?>

    <form method="POST" onsubmit="return collect_data()">

        <fieldset>

            <legend>Login to BoiGhor</legend>

            <table>

                <tr>

                    <td>

                        <label for="loginAs">
                            Login as:
                        </label>

                    </td>

                    <td>

                        <select id="loginAs" name="loginAs">

                            <option value="Default"
                                <?php
                                if ($loginAs === "" || $loginAs === "Default") {
                                    echo "selected";
                                }
                                ?>>
                                Select Role
                            </option>

                            <option value="Admin"
                                <?php
                                if ($loginAs === "Admin") {
                                    echo "selected";
                                }
                                ?>>
                                Admin
                            </option>

                            <option value="Customer"
                                <?php
                                if ($loginAs === "Customer") {
                                    echo "selected";
                                }
                                ?>>
                                Customer
                            </option>

                            <option value="Delivery Man"
                                <?php
                                if ($loginAs === "Delivery Man") {
                                    echo "selected";
                                }
                                ?>>
                                Delivery Man
                            </option>

                        </select>

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
                            value="<?php echo htmlspecialchars($email); ?>"
                            placeholder="Enter your Email"
                        >

                        <p id="emailError"></p>

                    </td>

                </tr>

                <tr>

                    <td>

                        <label for="password">
                            Password:
                        </label>

                    </td>

                    <td>

                        <input
                            type="password"
                            id="password"
                            name="password"
                        >

                    </td>

                </tr>

                <tr>

                    <td>

                        <input
                            type="checkbox"
                            id="rememberMe"
                            name="rememberMe"
                            value="1"
                            <?php
                            if ($remember) {
                                echo "checked";
                            }
                            ?>
                        >

                        <label for="rememberMe">
                            <font color="red">
                                Remember Me
                            </font>
                        </label>

                    </td>

                </tr>

                <tr>

                    <td>

                        <input
                            type="submit"
                            id="logIn"
                            name="logIn"
                            value="Log In"
                        >

                        <br>
                        <br>

                        <button
                            type="button"
                            id="signUP"
                            name="signUP"
                            onclick="window.location.href='signup.php'"
                        >
                            Sign Up
                        </button>

                    </td>

                </tr>

            </table>

        </fieldset>

    </form>

</div>

</body>

</html>