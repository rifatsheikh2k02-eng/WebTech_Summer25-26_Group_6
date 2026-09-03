function collect_data() {

    let role = document.getElementById("loginAs").value;
    let email = document.getElementById("userEmail").value.trim();
    let password = document.getElementById("password").value;

    let emailError = document.getElementById("emailError");

    emailError.innerHTML = "";

    if (role === "Default") {
        alert("Please select a role.");
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

    if (password === "") {
        alert("Password cannot be empty.");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters.");
        return false;
    }

    return true;
}