<!DOCTYPE html>

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VibeSpace</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<nav class="navbar">

    <div class="nav-container">


        <!-- LOGO -->

        <a href="index.php" class="logo">

            <span class="logo-icon">
                V
            </span>

            <span class="logo-text">
                VibeSpace
            </span>

        </a>


        <!-- NAVIGATION -->

        <div class="nav-links">


            <a href="index.php">
                Home
            </a>


            <?php if (isset($_SESSION['user_id'])): ?>


                <a href="timeline.php">
                    Timeline
                </a>


                <a href="profile.php">
                    Profile
                </a>


                <a href="logout.php" class="nav-button">
                    Logout
                </a>


            <?php else: ?>


                <a href="login.php">
                    Login
                </a>


                <a href="register.php" class="nav-button">
                    Sign Up
                </a>


            <?php endif; ?>


        </div>


    </div>

</nav>