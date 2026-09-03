function collect_data() {

    let name = document.getElementById("userName").value.trim();
    let email = document.getElementById("userEmail").value.trim();
    let phone = document.getElementById("userPhoneNumber").value.trim();
    let address = document.getElementById("userAddress").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPass").value;

    let nameError = document.getElementById("NameError");
    let emailError = document.getElementById("EmailError");

    nameError.innerHTML = "";
    emailError.innerHTML = "";

    if (name === "") {
        nameError.innerHTML = "Name cannot be empty.";
        return false;
    }

    if (name.length < 3) {
        nameError.innerHTML = "Name must be at least 3 characters.";
        return false;
    }

    let namePattern = /^[A-Za-z ]+$/;

    if (!namePattern.test(name)) {
        nameError.innerHTML =
            "Name can contain only letters and spaces.";
        return false;
    }

    if (email === "") {
        emailError.innerHTML = "Email cannot be empty.";
        return false;
    }

    let emailPattern =
        /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (!emailPattern.test(email)) {
        emailError.innerHTML = "Please enter a valid email.";
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

    if (password === "") {
        alert("Password cannot be empty.");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters.");
        return false;
    }

    if (confirmPassword === "") {
        alert("Please confirm your password.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Password does not match.");
        return false;
    }

    return true;
}