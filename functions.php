<?php
// Function to validate and sanitize input data
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to redirect with message
function redirect($location, $message = '') {
    if (!empty($message)) {
        echo "<script>alert('$message');</script>";
    }
    echo "<script>window.location.href='$location';</script>";
    exit();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['email']);
}

// Function to get current user email
function getCurrentUserEmail() {
    return $_SESSION['email'] ?? '';
}

// Function to get current user name
function getCurrentUserName() {
    return $_SESSION['name'] ?? '';
}

// Function to get current user image
function getCurrentUserImage() {
    return $_SESSION['image'] ?? '';
}
?> 