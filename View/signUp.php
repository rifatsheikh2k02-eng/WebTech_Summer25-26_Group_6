<?php
    include "../Controller/signUpValidation.php";
?>

<!DOCTYPE html>
<html lang="en-US"> 
<head> 
    <meta charset="UTF-8"> 
    <link rel="stylesheet" href="../Design/login.css"> 
    <script src="../JS/signUp.js"></script> 
    <title>Signup</title> 
</head> 

<body> 

<div class="container"> 

<form method="POST" onsubmit="return collect_data()"> 

<fieldset> 

<legend>Create account to BoiGhor</legend> 

<table> 

<tr> 
    <td>
        <label for="userName">Full name:</label>
    </td> 

    <td> 
        <input type="text" id="userName" name="userName" placeholder="Enter Full Name"> 
        <p id="NameError"></p>
    </td> 
</tr> 

<tr> 
    <td>
        <label for="userEmail">Email:</label>
    </td> 

    <td> 
        <input type="email" id="userEmail" name="userEmail" placeholder="Enter your Email"> 
        <p id="EmailError"></p>
    </td> 
</tr> 

<tr> 
    <td>
        <label for="userPhoneNumber">Mobile:</label>
    </td> 

    <td> 
        <input type="tel" id="userPhoneNumber" name="userPhoneNumber" placeholder="Enter your phone number"> 
    </td> 
</tr> 

<tr> 
    <td>
        <label for="userAddress">Address:</label>
    </td> 

    <td> 
        <textarea id="userAddress" name="userAddress" rows="5" cols="20" style="resize:none" placeholder="Your Address Here"></textarea> 
    </td> 
</tr> 

<tr> 
    <td>
        <label for="password">Password:</label>
    </td> 

    <td> 
        <input type="password" id="password" name="password"> 
    </td> 
</tr> 

<tr> 
    <td>
        <label for="confirmPass">Confirm Password:</label>
    </td> 

    <td> 
        <input type="password" id="confirmPass" name="confirmPass"> 
    </td>
</tr>

<tr>
    <td>
        <input type="submit" id="signUp" name="signUp" value="Create Account"> 
        <br>
        <br>
        <button type="button" id="back" name="back" onclick="window.location.href='login.php'">Back</button> 
    </td>
</tr>

</table>

</fieldset>

</form>

</div>

</body>
</html>
```
