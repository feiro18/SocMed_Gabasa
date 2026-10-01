<?php

session_start();

include '../db/dbconnect.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $user_id = $_SESSION['user_id'];

    $content = trim($_POST['content']);


    // Make sure post isn't empty
    if ($content == "") {

        header("Location: ../timeline.php");

        exit();

    }


    // Insert post
    $sql = "
        INSERT INTO posts (user_id, content)
        VALUES (?, ?)
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "is",
        $user_id,
        $content
    );


    if (mysqli_stmt_execute($stmt)) {

        header("Location: ../timeline.php");

        exit();

    } else {

        echo "Error creating post: " . mysqli_error($conn);

    }

}

?>