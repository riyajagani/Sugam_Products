document.getElementById("contactForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form from submitting

    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let message = document.getElementById("message").value.trim();
    let responseMessage = document.getElementById("responseMessage");

    if (name === "" || email === "" || message === "") {
        responseMessage.style.color = "red";
        responseMessage.textContent = "All fields are required!";
        return;
    }

    if (!validateEmail(email)) {
        responseMessage.style.color = "red";
        responseMessage.textContent = "Please enter a valid email address!";
        return;
    }

    responseMessage.style.color = "green";
    responseMessage.textContent = "Message sent successfully!";
    this.reset(); // Reset form after successful submission
});

// Email validation function
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
