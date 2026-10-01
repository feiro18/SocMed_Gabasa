<?php

session_start();

include 'db/dbconnect.php';
include 'views/navbar.php';

?>

<div class="hero">

    <div class="hero-content">

        <div class="glass-card hero-card">

            <div class="hero-icon">
                V
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>

                <h1>
                    Welcome back,
                    <?php echo htmlspecialchars($_SESSION['first_name']); ?>!
                </h1>

                <p>
                    Connect, share, and express yourself with your community.
                </p>

                <div class="hero-buttons">

                    <a
                        href="timeline.php"
                        class="primary-button"
                    >
                        Go to Timeline
                    </a>

                    <a
                        href="profile.php"
                        class="secondary-button"
                    >
                        View Profile
                    </a>

                </div>

            <?php else: ?>

                <h1>
                    Welcome to VibeSpace
                </h1>

                <p>
                    Connect, share, and express yourself with your community.
                </p>

                <div class="hero-buttons">

                    <a
                        href="register.php"
                        class="primary-button"
                    >
                        Create Account
                    </a>

                    <a
                        href="login.php"
                        class="secondary-button"
                    >
                        Sign In
                    </a>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php

include 'views/footer.php';

?>