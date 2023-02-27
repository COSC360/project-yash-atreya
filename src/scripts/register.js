document.addEventListener('DOMContentLoaded', function() {
    // Get the form by its ID
    const form = document.getElementById('register-form');

    // Get the form elements
    const email = document.getElementById('email');
    const username = document.getElementById('username');
    const password = document.getElementById('password');

    // Validate the form before submission
    form.onsubmit = (e) => {
        if(!email.value || !username.value || !password.value) {
            // TODO: Remove alert and highlight the border of the input
            alert('Please fill in the fields');
            e.preventDefault();
        }

        if(email.value && !validateEmail(email.value)) {
            // TODO: Message to user to correct the email
            alert('Please enter a valid email');
            e.preventDefault();
        }

        if(password.value && !validatePassword(password.value)) {
            // TODO: Message for password length
            alert('Password must be between 8 and 15 characters and cannot use any special characters');
            e.preventDefault();
        }

        if(username.value && !validateUsername(username.value)) {
            // TODO: Message for username
            alert('Username must be between 4 and 10 characters and cannot use any special characters');
            e.preventDefault();
        }
    }
});

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);

    // TODO: Check if email is already taken
}

// Validate Username: It should have only numbers and letters and a max length of 10 characters and atleat 4 characters
function validateUsername(username) {
    const regex = /^[a-zA-Z0-9]{4,10}$/;
    return regex.test(username);

    // TODO: Check if username is already taken
}

// Validate Password: It should have only numbers and letters and a max length of 15 characters and atleat 8 characters
function validatePassword(password) {
    const regex = /^[a-zA-Z0-9]{8,15}$/;
    return regex.test(password);
}