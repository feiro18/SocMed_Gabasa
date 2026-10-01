<?php

session_start();

include 'db/dbconnect.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();

}


// Get current user's profile picture
$user_id = $_SESSION['user_id'];

$user_sql = "
    SELECT profile_picture
    FROM users
    WHERE id = ?
";

$user_stmt = mysqli_prepare($conn, $user_sql);

mysqli_stmt_bind_param(
    $user_stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($user_stmt);

$user_result = mysqli_stmt_get_result($user_stmt);

$current_user = mysqli_fetch_assoc($user_result);


include 'views/navbar.php';

?>

<div class="timeline-page">

    <div class="timeline-container">


        <!-- Page Title -->

        <div class="timeline-header">

            <h1>Timeline</h1>

            <p>
                See what everyone is sharing.
            </p>

        </div>


        <!-- Create Post -->

        <div class="glass-card create-post-card">

            <div class="create-post-header">

                <div class="profile-circle">
                    <?php echo strtoupper(substr($_SESSION['first_name'], 0, 1)); ?>
                </div>

                <div>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $_SESSION['first_name'] . " " . $_SESSION['last_name']
                        );
                        ?>
                    </strong>

                    <p>
                        Share something with everyone
                    </p>

                </div>

            </div>


            <form action="models/add_post.php" method="POST">

                <textarea
                    name="content"
                    placeholder="What's on your mind?"
                    required
                ></textarea>


                <div class="create-post-footer">

                    <span>
                        Share your thoughts
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


        <!-- Timeline Posts -->

        <div class="posts-section">

            <?php

            $sql = "
                SELECT
                    posts.id,
                    posts.user_id,
                    posts.content,
                    posts.created_at,
                    users.first_name,
                    users.last_name,

                    (
                        SELECT COUNT(*)
                        FROM post_likes
                        WHERE post_likes.post_id = posts.id
                    ) AS like_count,

                    (
                        SELECT COUNT(*)
                        FROM post_likes
                        WHERE post_likes.post_id = posts.id
                        AND post_likes.user_id = ?
                    ) AS user_liked

                FROM posts

                INNER JOIN users
                ON posts.user_id = users.id

                ORDER BY posts.created_at DESC
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $_SESSION['user_id']
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);


            if (mysqli_num_rows($result) > 0):

                while ($post = mysqli_fetch_assoc($result)):

            ?>

                    <div class="glass-card post-card">


                        <div class="post-header">

<div class="profile-circle">

    <?php if (
        !empty($post['profile_picture']) &&
        $post['profile_picture'] !== 'default-profile.png'
    ): ?>

        <img
            src="uploads/<?php echo htmlspecialchars($post['profile_picture']); ?>"
            alt="Profile Picture"
        >

    <?php else: ?>

        <?php
        echo strtoupper(
            substr($post['first_name'], 0, 1)
        );
        ?>

    <?php endif; ?>

</div>


    <div class="post-user">

        <strong>

            <?php
            echo htmlspecialchars(
                $post['first_name'] .
                " " .
                $post['last_name']
            );
            ?>

        </strong>

        <span>

            <?php
            echo date(
                "M d, Y • h:i A",
                strtotime($post['created_at'])
            );
            ?>

        </span>

    </div>


    <?php if ($post['user_id'] == $_SESSION['user_id']): ?>

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
                                htmlspecialchars($post['content'])
                            );
                            ?>

                        </div>
                        <div class="post-actions">

                            <a
                                href="models/like_post.php?id=<?php echo $post['id']; ?>"
                                class="like-button <?php echo $post['user_liked'] ? 'liked' : ''; ?>"
                            >

                                <?php if ($post['user_liked']): ?>

                                    ♥ Liked

                                <?php else: ?>

                                    ♡ Like

                                <?php endif; ?>

                            </a>

                            <span class="like-count">

                                <?php echo $post['like_count']; ?>

                                <?php
                                echo $post['like_count'] == 1
                                    ? ' like'
                                    : ' likes';
                                ?>

                            </span>

                        </div>


                    </div>

            <?php

                endwhile;

            else:

            ?>

                <div class="glass-card empty-posts">

                    <div class="empty-icon">
                        +
                    </div>

                    <h2>No posts yet</h2>

                    <p>
                        Be the first person to share something!
                    </p>

                </div>

            <?php

            endif;

            ?>

        </div>

    </div>

</div>


<?php

include 'views/footer.php';

?>