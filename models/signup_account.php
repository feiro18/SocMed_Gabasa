<?php

include '../db/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    // Check if passwords match
    if ($password != $confirm_password) {

        die("Passwords do not match.");

    }


    // Check if email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";

    $check_stmt = mysqli_prepare($conn, $check_sql);

    mysqli_stmt_bind_param($check_stmt, "s", $email);

    mysqli_stmt_execute($check_stmt);

    mysqli_stmt_store_result($check_stmt);


    if (mysqli_stmt_num_rows($check_stmt) > 0) {

        die("This email is already registered.");

    }


    // Encrypt password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // Insert user
    $sql = "INSERT INTO users
            (email, first_name, last_name, password)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $email,
        $first_name,
        $last_name,
        $hashed_password
    );


    if (mysqli_stmt_execute($stmt)) {

        header("Location: ../login.php");

        exit();

    } else {

        echo "Registration failed: " . mysqli_error($conn);

    }

}

?>