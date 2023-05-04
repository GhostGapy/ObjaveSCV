<?php 
session_start(); 
require_once 'connection.php'; 

if(!isset($_POST['submit']))
{
    header("location:register.html");
}
else
{
    $name = $_POST['username'];
    $email = $_POST['email'];
    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirm-password'];
    $role = $_POST['role'];

    // Validate input data
    if(empty($name) || empty($email) || empty($pass1) || empty($pass2) || ($pass1 != $pass2)) {
        // Redirect back to registration page with error message
        $_SESSION['error'] = "Please fill all fields and make sure passwords match.";
        header("Location: register.html");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($pass1, PASSWORD_DEFAULT);

    // Insert the user into the database
    $sql = "INSERT INTO users (name, email, password, user_type) VALUES ('$name', '$email', '$hashed_password', '$role')"; 
    mysqli_query($link, $sql);

    // Redirect to login page with success message
    $_SESSION['success'] = "User registered successfully. You can now login.";
    header("Location: login.html");
    exit();
}
?>
