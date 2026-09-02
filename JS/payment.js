function validatePayment() {

    let cardName =
        document.getElementById("cardName").value.trim();

    let cardNumber =
        document.getElementById("cardNumber").value.trim();

    let expiryDate =
        document.getElementById("expiryDate").value.trim();

    let cvc =
        document.getElementById("cvc").value.trim();

    if (cardName === "") {
        alert("Card holder name cannot be empty.");
        return false;
    }

    if (cardName.length < 3) {
        alert("Please enter a valid card holder name.");
        return false;
    }

    let namePattern = /^[A-Za-z ]+$/;

    if (!namePattern.test(cardName)) {
        alert(
            "Card holder name can contain only letters and spaces."
        );
        return false;
    }

    if (cardNumber === "") {
        alert("Card number cannot be empty.");
        return false;
    }

    let cleanCardNumber =
        cardNumber.replace(/\s/g, "");

    if (!/^[0-9]{16}$/.test(cleanCardNumber)) {
        alert("Card number must contain 16 digits.");
        return false;
    }

    if (expiryDate === "") {
        alert("Expiry date cannot be empty.");
        return false;
    }

    if (!/^(0[1-9]|1[0-2])\/[0-9]{2}$/.test(expiryDate)) {
        alert("Invalid expiry date. Use MM/YY.");
        return false;
    }

    if (cvc === "") {
        alert("CVC cannot be empty.");
        return false;
    }

    if (!/^[0-9]{3}$/.test(cvc)) {
        alert("CVC must contain 3 digits.");
        return false;
    }

    return true;
}