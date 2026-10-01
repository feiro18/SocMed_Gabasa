<?php

include 'db/dbconnect.php';
include 'views/navbar.php';

?>

<div class="form-page">

    <div class="glass-card form-card">

        <div class="form-icon">
            V
        </div>

        <h1>Welcome Back</h1>

        <p class="form-subtitle">
            Sign in to continue to VibeSpace.
        </p>


        <form action="models/login_account.php" method="POST">


            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


<div class="form-group">

    <label for="password">
        Password
    </label>

    <input 
        type="password" 
        id="password" 
        name="password" 
        placeholder="Enter your password" 
        required 
    >

    <?php if (isset($_GET['error']) && $_GET['error'] == 'incorrect'): ?>

        <p class="login-error">
            Incorrect password.
        </p>

    <?php endif; ?>

</div>


            <button
                type="submit"
                class="form-button"
            >
                Sign In
            </button>


        </form>


        <p class="form-bottom">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </p>

    </div>

</div>


<?php

include 'views/footer.php';

?>