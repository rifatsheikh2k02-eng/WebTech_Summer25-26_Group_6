<?php

session_start();

include "../Model/db.php";

$loginAs = "";
$email = "";
$password = "";
$remember = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $loginAs = trim($_POST["loginAs"] ?? "");
    $email = trim($_POST["userEmail"] ?? "");
    $password = $_POST["password"] ?? "";
    $remember = isset($_POST["rememberMe"]);

    $valid = true;

    if (empty($loginAs) || $loginAs == "Default") {

        $message = "Please select a role";
        $valid = false;
    }

    if (empty($email)) {

        $message = "Email cannot be empty";
        $valid = false;

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email";
        $valid = false;
    }

    if (empty($password)) {

        $message = "Password cannot be empty";
        $valid = false;

    } elseif (strlen($password) < 5) {

        $message = "Password must be at least 5 characters";
        $valid = false;
    }

    if ($valid) {

        $sql = "SELECT * FROM users WHERE email = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                if ($user["role"] == $loginAs) {

                    $_SESSION["logged_in"] = true;
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["username"] = $user["username"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["role"] = $user["role"];

                    $jsonfile = "../Model/users.json";

                    $users = [];

                    if (file_exists($jsonfile)) {

                        $jsonData = file_get_contents($jsonfile);

                        $users = json_decode($jsonData, true) ?? [];

                    }

                    $users[] = [
                        "username" => $user["username"],
                        "email" => $user["email"],
                        "role" => $user["role"],
                        "timestamp" => time()
                    ];

                    file_put_contents(
                        $jsonfile,
                        json_encode($users, JSON_PRETTY_PRINT)
                    );

                    if ($remember) {

                        setcookie(
                            "remember_user",
                            $email,
                            time() + (86400 * 30),
                            "/"
                        );

                    } else {

                        setcookie(
                            "remember_user",
                            "",
                            time() - 3600,
                            "/"
                        );
                    }

                    if ($user["role"] == "Customer") {

                        header("Location: ../View/customerBrowse.php");
                        exit;

                    } elseif ($user["role"] == "Admin") {

                        header("Location: ../View/adminDashboard.php");
                        exit;

                    } elseif ($user["role"] == "Delivery Man") {

                        header("Location: ../View/deliveryManBrowse.php");
                        exit;
                    }

                } else {

                    $message = "Selected role does not match your account";
                }

            } else {

                $message = "Invalid Email or Password";
            }

        } else {

            $message = "Invalid Email or Password";
        }
    }
}

?>