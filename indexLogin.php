<!DOCTYPE html>
<html>
<head>
    
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- HTML Part -->
    <div class="login-box">
        <div class="login-header">
            <header>Welcome</header>
            <p>We are happy to have you back!</p>
        </div>

        <form method="post" action="login.php">
            <div class="checkbox-forgot">
                <section>
                    <div class="checkbox">
                        <input type="radio" id="admin" name="userType" value="admin" required>
                        <label for="admin">Admin</label>
                    </div>
                    <div class="checkbox">
                        <input type="radio" id="employee" name="userType" value="employee">
                        <label for="employee">Employee</label>
                    </div>
                </section>
            </div>

            <div class="input-box">
                <input type="text" class="input-field" name="email" id="email" autocomplete="on" required>
                <label for="email">Email</label>
            </div>

            <div class="input-box">
                <input type="password" class="input-field" name="password" id="password" autocomplete="on" required>
                <label for="password">Password</label>
            </div>

            <div class="input-box">
                <input type="submit" class="input-submit" value="Sign In" name="send">
            </div>
        </form>
<!--
        <div class="sign-up">
            <p>Don't have an account? <a href="signup.html">Sign up</a></p>
        </div>
  <div class="forgot">
            <section>
                <a href="forgot.php" class="forgot-link">Forgot password?</a>
            </section>
        </div>
        -->
       
    </div>
</body>
</html>
