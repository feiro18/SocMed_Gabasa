<?php

session_start();

include 'db/dbconnect.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();

}


// Get logged-in user's ID
$user_id = $_SESSION['user_id'];


// Get current user information
$sql = "
    SELECT
        first_name,
        last_name,
        email,
        bio
    FROM users
    WHERE id = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);


// Include navbar
include 'views/navbar.php';

?>


<div class="edit-profile-page">

    <div class="edit-profile-card glass-card">


        <div class="edit-profile-header">

            <h1>Edit Profile</h1>

            <p>
                Update your VibeSpace profile information.
            </p>

        </div>


        <form
            action="models/update_profile.php"
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- EMAIL -->

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>"
                    disabled
                >

                <small>
                    Your email cannot be changed here.
                </small>

            </div>


            <!-- FIRST NAME -->

            <div class="form-group">

                <label for="first_name">
                    First Name
                </label>

                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    value="<?php echo htmlspecialchars($user['first_name']); ?>"
                    required
                >

            </div>


            <!-- LAST NAME -->

            <div class="form-group">

                <label for="last_name">
                    Last Name
                </label>

                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="<?php echo htmlspecialchars($user['last_name']); ?>"
                    required
                >

            </div>


            <!-- BIO -->

            <div class="form-group">

                <label for="bio">
                    Bio
                </label>

                <textarea
                    id="bio"
                    name="bio"
                    maxlength="255"
                    placeholder="Tell people something about yourself..."
                ><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>

                <small>
                    Maximum 255 characters.
                </small>

            </div>
            <!-- PROFILE PICTURE -->

<div class="form-group">

    <label for="profile_picture">
        Profile Picture
    </label>

    <input
        type="file"
        id="profile_picture"
        name="profile_picture"
        accept="image/jpeg,image/png,image/webp"
    >

    <small>
        JPG, PNG or WEBP. Maximum 2MB.
    </small>

</div>
<!-- BANNER -->

<div class="form-group">

    <label for="banner">
        Profile Banner
    </label>

    <input
        type="file"
        id="banner"
        name="banner"
        accept="image/jpeg,image/png,image/webp"
    >

    <small>
        JPG, PNG or WEBP. Maximum 2MB.
    </small>

</div>
            <!-- BUTTONS -->

            <div class="edit-profile-buttons">

                <a
                    href="profile.php"
                    class="cancel-button"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="form-button"
                >
                    Save Changes
                </button>

            </div>


        </form>


    </div>

</div>


<?php

include 'views/footer.php';

?>