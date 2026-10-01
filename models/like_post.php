<?php

session_start();

include '../db/dbconnect.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}


// Get user and post IDs
$user_id = $_SESSION['user_id'];

$post_id = $_GET['id'] ?? 0;


// Make sure post ID is valid
if ($post_id <= 0) {

    header("Location: ../timeline.php");

    exit();

}


// Check if the user already liked the post
$check_sql = "
    SELECT id
    FROM post_likes
    WHERE post_id = ?
    AND user_id = ?
";

$check_stmt = mysqli_prepare($conn, $check_sql);

mysqli_stmt_bind_param(
    $check_stmt,
    "ii",
    $post_id,
    $user_id
);

mysqli_stmt_execute($check_stmt);

$check_result = mysqli_stmt_get_result($check_stmt);


// If already liked, remove the like
if (mysqli_num_rows($check_result) > 0) {

    $like = mysqli_fetch_assoc($check_result);

    $delete_sql = "
        DELETE FROM post_likes
        WHERE id = ?
    ";

    $delete_stmt = mysqli_prepare(
        $conn,
        $delete_sql
    );

    mysqli_stmt_bind_param(
        $delete_stmt,
        "i",
        $like['id']
    );

    mysqli_stmt_execute($delete_stmt);


// Otherwise, add a like
} else {

    $insert_sql = "
        INSERT INTO post_likes
        (post_id, user_id)
        VALUES (?, ?)
    ";

    $insert_stmt = mysqli_prepare(
        $conn,
        $insert_sql
    );

    mysqli_stmt_bind_param(
        $insert_stmt,
        "ii",
        $post_id,
        $user_id
    );

    mysqli_stmt_execute($insert_stmt);

}


// Return to timeline
header("Location: ../timeline.php");

exit();

?>