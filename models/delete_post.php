<?php

session_start();

include '../db/dbconnect.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}


// Check if a post ID was provided
if (!isset($_GET['id'])) {

    header("Location: ../timeline.php");

    exit();

}


$post_id = $_GET['id'];

$user_id = $_SESSION['user_id'];


// Delete only if the post belongs to the logged-in user
$sql = "
    DELETE FROM posts
    WHERE id = ?
    AND user_id = ?
";


$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $post_id,
    $user_id
);


if (mysqli_stmt_execute($stmt)) {

    header("Location: ../timeline.php");

    exit();

} else {

    echo "Error deleting post: " . mysqli_error($conn);

}

?>