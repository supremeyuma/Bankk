document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    console.log('Attempting to login with:');
    console.log('Email/Username:', email);
    console.log('Password:', password);

    // Here you would typically send this data to your backend for authentication
    // Example: using fetch API
    /*
    fetch('/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email, password: password }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Login successful!');
            // Redirect to dashboard or next page
            // window.location.href = '/dashboard';
        } else {
            alert('Login failed: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('An error occurred during login.');
    });
    */

    // For now, we'll just show an alert
    alert('Login form submitted. Check console for details.');
});
