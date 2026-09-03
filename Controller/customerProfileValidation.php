<?php

session_start();

include "../Model/db.php";


if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {

    header("Location: ../View/login.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $userId = $_SESSION["user_id"];


    $userName = trim($_POST["userName"] ?? "");

    $userEmail = trim($_POST["userEmail"] ?? "");

    $userPhoneNumber = trim($_POST["userPhoneNumber"] ?? "");

    $userAddress = trim($_POST["userAddress"] ?? "");

    $password = trim($_POST["password"] ?? "");

    $confirmPass = trim($_POST["confirmPass"] ?? "");

    if (

        empty($userName) ||

        empty($userEmail) ||

        empty($userPhoneNumber) ||

        empty($userAddress)

    ) {

        $_SESSION["profile_message"] = "All fields are required";

        header("Location: ../View/customerProfile.php");
        exit;
    }



    // Email format check

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {

        $_SESSION["profile_message"] = "Invalid email format";

        header("Location: ../View/customerProfile.php");
        exit;
    }


    $check = "SELECT * FROM users WHERE email = ? AND id != ?";


    $stmt = $conn->prepare($check);


    $stmt->bind_param(

        "ss",

        $userEmail,

        $userId

    );


    $stmt->execute();


    $result = $stmt->get_result();



    if ($result->num_rows > 0) {

        $_SESSION["profile_message"] =
            "Email already used by another account";

        header("Location: ../View/customerProfile.php");
        exit;
    }


    if (!empty($password)) {


        if ($password != $confirmPass) {

            $_SESSION["profile_message"] =
                "Password does not match";

            header("Location: ../View/customerProfile.php");
            exit;
        }



        if (strlen($password) < 5) {

            $_SESSION["profile_message"] =
                "Password must be at least 5 characters";

            header("Location: ../View/customerProfile.php");
            exit;
        }



        $hashedPassword = password_hash(

            $password,

            PASSWORD_DEFAULT

        );



        $sql = "UPDATE users SET

                username = ?,
                email = ?,
                phone = ?,
                address = ?,
                password = ?

                WHERE id = ?";



        $stmt = $conn->prepare($sql);



        $stmt->bind_param(

            "ssssss",

            $userName,

            $userEmail,

            $userPhoneNumber,

            $userAddress,

            $hashedPassword,

            $userId

        );


    } else {

        $sql = "UPDATE users SET

                username = ?,
                email = ?,
                phone = ?,
                address = ?

                WHERE id = ?";



        $stmt = $conn->prepare($sql);



        $stmt->bind_param(

            "sssss",

            $userName,

            $userEmail,

            $userPhoneNumber,

            $userAddress,

            $userId

        );

    }



    if ($stmt->execute()) {

        $_SESSION["username"] = $userName;

        $_SESSION["email"] = $userEmail;


        $_SESSION["profile_message"] =
            "Profile updated successfully";


        header("Location: ../View/customerProfile.php");

        exit;


    } else {


        $_SESSION["profile_message"] =
            "Profile update failed";


        header("Location: ../View/customerProfile.php");

        exit;

    }

}
?>
