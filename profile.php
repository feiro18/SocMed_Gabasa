<?php

session_start();

include 'db/dbconnect.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();

}

include 'views/navbar.php';


// Get logged-in user's ID
$user_id = $_SESSION['user_id'];


// Get user information
$sql = "
SELECT
    id,
    email,
    first_name,
    last_name,
    bio,
    profile_picture,
    banner,
    created_at
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


// Get user's posts
$post_sql = "
    SELECT
        id,
        content,
        created_at
    FROM posts
    WHERE user_id = ?
    ORDER BY created_at DESC
";

$post_stmt = mysqli_prepare($conn, $post_sql);

mysqli_stmt_bind_param(
    $post_stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($post_stmt);

$post_result = mysqli_stmt_get_result($post_stmt);

?>

<div class="profile-page">

    <div class="profile-container">


        <!-- =========================
             PROFILE HEADER
        ========================== -->

        <div class="glass-card profile-header">


            <!-- Banner -->

<div class="profile-banner">

    <?php

    $banner_file = $user['banner'];

    if (
        empty($banner_file) ||
        $banner_file === 'default-banner.jpg'
    ) {

        $banner_path =
            'images/default-banner.jpg';

    } else {

        $banner_path =
            'uploads/' . $banner_file;

    }

    ?>

    <img
        src="<?php echo htmlspecialchars($banner_path); ?>"
        alt="Profile Banner"
    >

</div>


            <!-- Profile Information -->

            <div class="profile-info">


<div class="profile-picture">

    <?php if (
        !empty($user['profile_picture']) &&
        $user['profile_picture'] !== 'default-profile.png'
    ): ?>

        <img
            src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>"
            alt="Profile Picture"
        >

    <?php else: ?>

        <?php
        echo strtoupper(
            substr($user['first_name'], 0, 1)
        );
        ?>

    <?php endif; ?>

</div>


<div class="profile-details">

    <div class="profile-name-row">

        <h1>

            <?php
            echo htmlspecialchars(
                $user['first_name'] .
                " " .
                $user['last_name']
            );
            ?>

        </h1>


        <a
            href="edit_profile.php"
            class="edit-profile-button"
        >
            Edit Profile
        </a>

    </div>


    <p>

        <?php
        echo htmlspecialchars($user['email']);
        ?>

    </p>


    <?php if (!empty($user['bio'])): ?>

        <p class="profile-bio">

            <?php
            echo nl2br(
                htmlspecialchars($user['bio'])
            );
            ?>

        </p>

    <?php endif; ?>


    <span>

        Joined

        <?php
        echo date(
            "F Y",
            strtotime($user['created_at'])
        );
        ?>

    </span>

</div>

            </div>


        </div>


        <!-- =========================
             CREATE POST
        ========================== -->

        <div class="glass-card profile-create-post">

            <h2>Create a Post</h2>

            <form
                action="models/add_post.php"
                method="POST"
            >

                <textarea
                    name="content"
                    placeholder="What's on your mind?"
                    required
                ></textarea>


                <div class="profile-post-footer">

                    <span>
                        Share something with VibeSpace
                    </span>

                    <button
                        type="submit"
                        class="form-button post-button"
                    >
                        Post
                    </button>

                </div>

            </form>

        </div>


        <!-- =========================
             USER POSTS
        ========================== -->

        <div class="profile-posts">


            <div class="profile-post-title">

                <h2>Your Posts</h2>

                <span>
                    <?php echo mysqli_num_rows($post_result); ?>
                    posts
                </span>

            </div>


            <?php if (mysqli_num_rows($post_result) > 0): ?>


                <div class="posts-section">


                    <?php while ($post = mysqli_fetch_assoc($post_result)): ?>


                        <div class="glass-card post-card">


                            <div class="post-header">


                                <div class="profile-circle">

                                    <?php
                                    echo strtoupper(
                                        substr(
                                            $user['first_name'],
                                            0,
                                            1
                                        )
                                    );
                                    ?>

                                </div>


                                <div class="post-user">

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $user['first_name'] .
                                            " " .
                                            $user['last_name']
                                        );
                                        ?>

                                    </strong>


                                    <span>

                                        <?php
                                        echo date(
                                            "M d, Y • h:i A",
                                            strtotime(
                                                $post['created_at']
                                            )
                                        );
                                        ?>

                                    </span>

                                </div>
                                <?php if ($post['id'] && $user_id == $_SESSION['user_id']): ?>

    <a
        href="models/delete_post.php?id=<?php echo $post['id']; ?>"
        class="delete-post"
        onclick="return confirm('Are you sure you want to delete this post?');"
    >
        Delete
    </a>

<?php endif; ?>


                            </div>


                            <div class="post-content">

                                <?php
                                echo nl2br(
                                    htmlspecialchars(
                                        $post['content']
                                    )
                                );
                                ?>

                            </div>


                        </div>


                    <?php endwhile; ?>


                </div>


            <?php else: ?>


                <div class="glass-card empty-posts">

                    <div class="empty-icon">
                        +
                    </div>

                    <h2>No posts yet</h2>

                    <p>
                        You haven't shared anything yet.
                    </p>

                </div>


            <?php endif; ?>


        </div>


    </div>  

</div>


<?php

include 'views/footer.php';

?>