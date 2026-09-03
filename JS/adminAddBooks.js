function validateAddBook() {

    let title = document.getElementById("title").value.trim();
    let author = document.getElementById("author").value.trim();
    let category = document.getElementById("category").value.trim();
    let isbn = document.getElementById("isbn").value.trim();
    let price = document.getElementById("price").value;
    let quantity = document.getElementById("quantity").value;
    let description = document.getElementById("description").value.trim();
    let image = document.getElementById("image").files[0];

    if (title === "") {
        alert("Book title cannot be empty.");
        return false;
    }

    if (title.length < 2) {
        alert("Book title must be at least 2 characters.");
        return false;
    }

    if (author === "") {
        alert("Author name cannot be empty.");
        return false;
    }

    if (author.length < 2) {
        alert("Author name must be at least 2 characters.");
        return false;
    }

    if (category === "") {
        alert("Category cannot be empty.");
        return false;
    }

    if (isbn === "") {
        alert("ISBN cannot be empty.");
        return false;
    }

    let isbnPattern = /^[0-9-]{10,17}$/;

    if (!isbnPattern.test(isbn)) {
        alert("Please enter a valid ISBN.");
        return false;
    }

    if (price === "" || Number(price) < 0) {
        alert("Please enter a valid price.");
        return false;
    }

    if (
        quantity === "" ||
        Number(quantity) < 0 ||
        !Number.isInteger(Number(quantity))
    ) {
        alert("Quantity must be a non-negative whole number.");
        return false;
    }

    if (description.length > 1000) {
        alert("Description cannot exceed 1000 characters.");
        return false;
    }

    if (!image) {
        alert("Please select a book image.");
        return false;
    }

    let allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/gif",
        "image/webp"
    ];

    if (!allowedTypes.includes(image.type)) {
        alert(
            "Only JPG, JPEG, PNG, GIF and WEBP images are allowed."
        );
        return false;
    }

    return true;
}