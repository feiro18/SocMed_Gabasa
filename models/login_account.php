<?php

session_start();

include '../db/dbconnect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];


    // Find the user
    $sql = "
        SELECT
            id,
            email,
            first_name,
            last_name,
            password
        FROM users
        WHERE email = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);


    // Check if user exists
    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);


        // Check password
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];

            $_SESSION['email'] = $user['email'];

            $_SESSION['first_name'] = $user['first_name'];

            $_SESSION['last_name'] = $user['last_name'];


            // Login successful
            header("Location: ../timeline.php");

            exit();


} else {

    header("Location: ../login.php?error=incorrect");

    exit();

}


    } else {

        // Account does not exist
        $_SESSION['login_error'] = "Account not found.";

        header("Location: ../login.php");

        exit();
        
    }

}

?>