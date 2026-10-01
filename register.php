<?php

include 'db/dbconnect.php';
include 'views/navbar.php';

?>

<div class="form-page">

    <div class="glass-card form-card">

        <div class="form-icon">
            V
        </div>

        <h1>Create Account</h1>

        <p class="form-subtitle">
            Join VibeSpace and start sharing.
        </p>

        <form action="models/signup_account.php" method="POST">

            <div class="form-group">

                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label for="first_name">First Name</label>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        placeholder="First name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="last_name">Last Name</label>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        placeholder="Last name"
                        required
                    >

                </div>

            </div>


            <div class="form-group">

                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >

            </div>


            <div class="form-group">

                <label for="confirm_password">Confirm Password</label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm your password"
                    required
                >

            </div>


            <button type="submit" class="form-button">
                Create Account
            </button>

        </form>


        <p class="form-bottom">

            Already have an account?

            <a href="login.php">
                Sign In
            </a>

        </p>

    </div>

</div>

<?php

include 'views/footer.php';

?>