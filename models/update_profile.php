<?php

session_start();

include '../db/dbconnect.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit();

}


// Make sure the form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../profile.php");

    exit();

}


$user_id = $_SESSION['user_id'];


// Get form values
$first_name = trim($_POST['first_name'] ?? '');

$last_name = trim($_POST['last_name'] ?? '');

$bio = trim($_POST['bio'] ?? '');


// Basic validation
if ($first_name === '' || $last_name === '') {

    echo "First name and last name are required.";

    exit();

}


// Upload folder
$upload_dir = '../uploads/';


// Allowed image types
$allowed_types = [
    'image/jpeg',
    'image/png',
    'image/webp'
];


// Maximum file size
$max_size = 2 * 1024 * 1024;


// Get current profile information
$get_user_sql = "
    SELECT
        profile_picture,
        banner
    FROM users
    WHERE id = ?
";

$get_user_stmt = mysqli_prepare(
    $conn,
    $get_user_sql
);

mysqli_stmt_bind_param(
    $get_user_stmt,
    "i",
    $user_id
);

mysqli_stmt_execute(
    $get_user_stmt
);

$get_user_result = mysqli_stmt_get_result(
    $get_user_stmt
);

$current_user = mysqli_fetch_assoc(
    $get_user_result
);


// Keep current filenames
$profile_picture =
    $current_user['profile_picture'];

$banner =
    $current_user['banner'];


// =====================================
// PROFILE PICTURE UPLOAD
// =====================================

if (
    isset($_FILES['profile_picture']) &&
    $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE
) {

    $file = $_FILES['profile_picture'];


    if ($file['error'] !== UPLOAD_ERR_OK) {

        echo "There was an error uploading the profile picture.";

        exit();

    }


    if ($file['size'] > $max_size) {

        echo "Profile picture is too large. Maximum size is 2MB.";

        exit();

    }


    $file_type = mime_content_type(
        $file['tmp_name']
    );


    if (!in_array($file_type, $allowed_types)) {

        echo "Invalid profile picture type.";

        exit();

    }


    $extension = pathinfo(
        $file['name'],
        PATHINFO_EXTENSION
    );


    $new_filename =
        'profile_' .
        $user_id .
        '_' .
        time() .
        '.' .
        strtolower($extension);


    $destination =
        $upload_dir .
        $new_filename;


    if (
        !move_uploaded_file(
            $file['tmp_name'],
            $destination
        )
    ) {

        echo "Failed to save profile picture.";

        exit();

    }


    $profile_picture = $new_filename;

}


// =====================================
// BANNER UPLOAD
// =====================================

if (
    isset($_FILES['banner']) &&
    $_FILES['banner']['error'] !== UPLOAD_ERR_NO_FILE
) {

    $file = $_FILES['banner'];


    if ($file['error'] !== UPLOAD_ERR_OK) {

        echo "There was an error uploading the banner.";

        exit();

    }


    if ($file['size'] > $max_size) {

        echo "Banner is too large. Maximum size is 2MB.";

        exit();

    }


    $file_type = mime_content_type(
        $file['tmp_name']
    );


    if (!in_array($file_type, $allowed_types)) {

        echo "Invalid banner type.";

        exit();

    }


    $extension = pathinfo(
        $file['name'],
        PATHINFO_EXTENSION
    );


    $new_filename =
        'banner_' .
        $user_id .
        '_' .
        time() .
        '.' .
        strtolower($extension);


    $destination =
        $upload_dir .
        $new_filename;


    if (
        !move_uploaded_file(
            $file['tmp_name'],
            $destination
        )
    ) {

        echo "Failed to save banner.";

        exit();

    }


    $banner = $new_filename;

}


// =====================================
// UPDATE DATABASE
// =====================================

$sql = "
    UPDATE users
    SET
        first_name = ?,
        last_name = ?,
        bio = ?,
        profile_picture = ?,
        banner = ?
    WHERE id = ?
";


$stmt = mysqli_prepare(
    $conn,
    $sql
);

mysqli_stmt_bind_param(
    $stmt,
    "sssssi",
    $first_name,
    $last_name,
    $bio,
    $profile_picture,
    $banner,
    $user_id
);


if (mysqli_stmt_execute($stmt)) {

    header("Location: ../profile.php");

    exit();

} else {

    echo "Error updating profile: " .
         mysqli_error($conn);

}

?>