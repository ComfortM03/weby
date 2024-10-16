document.getElementById("userProfileForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the default form submission

    const email = document.getElementById("email").value;
    const birthday = document.getElementById("birthday").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const messageDiv = document.getElementById("message");

    // Basic validation
    if (password !== confirmPassword) {
        messageDiv.innerText = "Passwords do not match!";
        return;
    }

    // Here you would send the data to the server using fetch or XMLHttpRequest
    const userData = {
        email,
        birthday,
        password
    };

    // Example: Sending data to a PHP script using fetch
    fetch("submit.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.innerText = data.message;
    })
    .catch(error => {
        messageDiv.innerText = "Error submitting the form.";
    });
});
