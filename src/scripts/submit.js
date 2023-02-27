document.addEventListener('DOMContentLoaded', function() {
    // Get the form by its ID
    const form = document.getElementById('submit-form');

    // Get the form elements
    const title = document.getElementById('title');
    const text = document.getElementById('text');
    const url = document.getElementById('url');

    // Validate the form before submission
    form.onsubmit = (e) => {
        if(!title.value || !text.value) {
            // TODO: Remove alert and highlight the border of the input
            alert('Please fill in all the fields');
            e.preventDefault();
        }

        if(url.value && !validateURL(url.value)) {
            // TODO: Remove alert and highlight the border of the input
            alert('Please enter a valid URL');
            e.preventDefault();
        }
    }
});

// URL validation function
function validateURL(url) {
    const regex = /^(http|https):\/\/[^ "]+$/;
    return regex.test(url);
}
