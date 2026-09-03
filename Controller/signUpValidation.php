<?php

session_start();

include "../Model/db.php";

$userName = "";
$userEmail = "";
$userPhoneNumber = "";
$userAddress = "";
$password = "";
$confirmPass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userName = trim($_POST["userName"] ?? "");
    $userEmail = trim($_POST["userEmail"] ?? "");
    $userPhoneNumber = trim($_POST["userPhoneNumber"] ?? "");
    $userAddress = trim($_POST["userAddress"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirmPass = trim($_POST["confirmPass"] ?? "");

    if ($password != $confirmPass) {

        echo "Password does not match";

    } else {

        $check = "SELECT * FROM users WHERE email = ?";

        $stmt = $conn->prepare($check);
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            echo "Email already exists";

        } else {

            $result = $conn->query(
                "SELECT id FROM users 
                 WHERE id LIKE 'cus_%' 
                 ORDER BY id DESC 
                 LIMIT 1"
            );

            if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();

                $lastId = $row["id"];

                $number = intval(substr($lastId, 4));

                $number++;

            } else {

                $number = 1001;

            }

            $userId = "cus_" . $number;

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $role = "Customer";

            $sql = "INSERT INTO users
                    (id, username, email, phone, address, password, role)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sssssss",
                $userId,
                $userName,
                $userEmail,
                $userPhoneNumber,
                $userAddress,
                $hashedPassword,
                $role
            );

            if ($stmt->execute()) {

                $_SESSION["signup_success"] =
                    "Signup successfully! You can login now.";

                header("Location: ../View/login.php");
                exit;

            } else {

                echo "Account creation failed";

            }
        }
    }
}

?>
