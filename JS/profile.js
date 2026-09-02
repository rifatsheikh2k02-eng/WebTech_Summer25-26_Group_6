function validateProfile() {

    let name = document.getElementById("userName").value.trim();
    let email = document.getElementById("userEmail").value.trim();
    let phone = document.getElementById("userPhoneNumber").value.trim();
    let address = document.getElementById("userAddress").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword =
        document.getElementById("confirmPass").value;

    if (name === "") {
        alert("Name cannot be empty.");
        return false;
    }

    if (name.length < 3) {
        alert("Name must be at least 3 characters.");
        return false;
    }

    let namePattern = /^[A-Za-z ]+$/;

    if (!namePattern.test(name)) {
        alert("Name can contain only letters and spaces.");
        return false;
    }

    if (email === "") {
        alert("Email cannot be empty.");
        return false;
    }

    let emailPattern =
        /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email.");
        return false;
    }

    if (phone === "") {
        alert("Phone number cannot be empty.");
        return false;
    }

    let phonePattern = /^01[3-9][0-9]{8}$/;

    if (!phonePattern.test(phone)) {
        alert(
            "Please enter a valid 11 digit Bangladesh phone number."
        );
        return false;
    }

    if (address === "") {
        alert("Address cannot be empty.");
        return false;
    }

    if (address.length < 5) {
        alert("Address must be at least 5 characters.");
        return false;
    }

    if (password !== "") {

        if (password.length < 6) {
            alert("New password must be at least 6 characters.");
            return false;
        }

        if (confirmPassword === "") {
            alert("Please confirm your new password.");
            return false;
        }

        if (password !== confirmPassword) {
            alert("Password does not match.");
            return false;
        }
    }

    if (password === "" && confirmPassword !== "") {
        alert("Please enter a new password first.");
        return false;
    }

    return true;
}