// Function to set a cookie
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Convert days to milliseconds
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/"; // Path set to root
}

// Function to get a cookie by name
function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';'); // Split cookies into an array
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length); // Trim leading spaces
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length); // Return cookie value
    }
    return null; // Return null if cookie not found
}

// Function to delete a cookie
function eraseCookie(name) {
    setCookie(name, "", -1); // Set the cookie with an expiry date in the past
}

// Function to generate a unique user ID
function generateUserId() {
    return 'user_' + Math.random().toString(36).substr(2, 9); // Generates a random user ID
}

// Function to check and set user ID cookie
function checkUserIdCookie() {
    let userId = getCookie('userId'); // Try to get the existing cookie
    if (!userId) { // If it doesn't exist, create a new one
        userId = generateUserId(); // Generate a unique ID
        setCookie('userId', userId, 30); // Set cookie for 30 days
    }
    return userId; // Return the user ID
}

// Check user ID when the page loads
document.addEventListener('DOMContentLoaded', function() {
    const userId = checkUserIdCookie();
    console.log("User ID:", userId); // For debugging: log the user ID
});
