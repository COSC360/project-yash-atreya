window.onload = function() {
    console.log("validate-login.js loaded");
    var loginForm = document.getElementById("login-form");

    loginForm.onsubmit = function(e) {
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;
        console.log("Username: " + username);
        console.log("Password: " + password);
        if (!validateUsername(username)) {
            alert("Please enter a valid username.\nUsername must be between 4 and 20 characters long and contain only letters and numbers.");
            e.preventDefault();
            return;
        }
        if(!validatePassword(password)) {
            alert("Please enter a valid password.\nPassword must be between 4 and 20 characters long.");
            e.preventDefault();
            return;
        }

        // If we get here, the username and password are valid
        // So we can submit the form

    }
}

function validateUsername(username) {
    // Check if null or undefined
    if (!username) {
        return false;
    }
    // Check if empty string
    if (username.length == 0) {
        return false;
    }
    // Check if only whitespace
    if (username.trim().length == 0) {
        return false;
    }
    // Check if username is too long
    if (username.length > 20) {
        return false;
    }
    // Check if username is too short
    if (username.length < 4) {
        return false;
    }
    // Check if username contains any invalid characters
    if (!username.match(/^[a-zA-Z0-9]+$/)) {
        return false;
    }
    return true;
}

function validatePassword(password) {
    // Check if null or undefined
    if (!password) {
        return false;
    }
    // Check if empty string
    if (password.length == 0) {
        return false;
    }
    // Check if only whitespace
    if (password.trim().length == 0) {
        return false;
    }
    // Check if password is too long
    if (password.length > 20) {
        return false;
    }
    // Check if password is too short
    if (password.length < 4) {
        return false;
    }
    return true;
}