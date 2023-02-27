document.addEventListener('DOMContentLoaded', function() {
    // Get the form by its ID
    const form = document.getElementById('login-form');

    // Get the form elements
    const username = document.getElementById('username');
    const password = document.getElementById('password');

    // Validate the form before submission
    form.onsubmit = (e) => {
        if(!username.value || !password.value) {
            alert('Please fill in the fields');
            e.preventDefault();
        }

        if(password.value && !validatePassword(password.value)) {
            alert('Password must be between 8 and 15 characters and cannot use any special characters');
            e.preventDefault();
        }

        if(username.value && !validateUsername(username.value)) {
            alert('Username must be between 4 and 10 characters and cannot use any special characters');
            e.preventDefault();
        }
    }
});

// Validate Username: It should have only numbers and letters and a max length of 10 characters and atleat 4 characters
function validateUsername(username) {
    const regex = /^[a-zA-Z0-9]{4,10}$/;
    return regex.test(username);
}

// Validate Password: It should have only numbers and letters and a max length of 15 characters and atleat 8 characters
function validatePassword(password) {
    const regex = /^[a-zA-Z0-9]{8,15}$/;
    return regex.test(password);
}