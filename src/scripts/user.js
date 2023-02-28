document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('user-form');

    // User Data
    var user = {
        username: document.getElementById('username').innerHTML,
        email: document.getElementById('email').value,
        bio: document.getElementById('bio').innerHTML,
        karma: document.getElementById('karma').innerHTML,
    }

    form.onsubmit = (e) => {
        // Check if the user has changed any of the fields
    }
});

// Validate email
function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

// Validate Username: It should have only numbers and letters and a max length of 10 characters and atleat 4 characters
function validateUsername(username) {
    const regex = /^[a-zA-Z0-9]{4,10}$/;
    return regex.test(username);
}
