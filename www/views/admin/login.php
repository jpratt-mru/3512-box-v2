<?php $pageTitle = 'Admin Login'; ?>
<?php include '../views/partials/header.php'; ?>

<div class="unique-container">

    <!-- Left Side: Welcome Message -->
    <div class="unique-welcome-message">
        <div class="unique-text">
            <span class="unique-text-1">Welcome to The Admin Login Page!</span> <br>
            <span class="unique-text-2">As an admin, you have the power to manage user content and view critical statistics for the platform. You'll be able to view and manage photos by flagging, deleting, or restoring images. Additionally, you can analyze platform stats to gain insights into total photos, popular cities, and flagged users. With easy navigation, you can access the different tools and features that will help maintain a smooth operation. Let's keep the platform clean and engaging, everything you need is at your fingertips. Enjoy exploring the Admin Dashboard!</span>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="unique-login-form-container">
        <div class="unique-form-content">
            <div class="unique-login-form">
                <div class="unique-title">Please Enter Login Details Here.</div>
                <form action="/admin" method="POST">
                    <!-- Display validation errors here -->
                    <?php if (!empty($error)): ?>
                        <div class="error-message" style="color: red;">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <div class="unique-input-boxes">
                        <div class="unique-input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="text" name="username" placeholder="Enter your username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                        <div class="unique-input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Enter your password">
                        </div>
                        <div class="unique-checkbox">
                            <label for="remember_me">
                                <input type="checkbox" name="remember_me" id="remember_me" <?php echo isset($_POST['remember_me']) ? 'checked' : ''; ?>> Remember me for 30 days
                            </label>
                        </div>
                        <div class="unique-button input-box">
                            <input type="submit" value="Log In Now">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../views/partials/footer.php'; ?>