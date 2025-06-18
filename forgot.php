<!DOCTYPE html>
<html>
<head>
    <!-- JavaScript part -->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- HTML Part -->
    <div class="login-box" style="height: 450px">
        <div class="login-header">
            <header></header>
            <p>Reset Your Password</p>
        </div>

        <form method="post" action="changePassword.php">
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
                <input type="text" class="input-field" name="email" id="email" autocomplete="off" required>
                <label for="email">Email</label>
            </div>

            <div class="input-box">
                <input type="password" class="input-field" name="newPassword" id="newPassword" autocomplete="off" required>
                <label for="password">New Password</label>
            </div>

             <div class="forgot">
            <button type="button" onclick="sendCode()">Send 6-Digit Code</button>

            </div>

            <div class="input-box">
                <input type="text" class="input-field" name="code" id="code" autocomplete="off" required>
                <label for="password">6-Digit Code</label>
            </div>
            

            <div class="input-box">
                <input type="submit" class="input-submit" value="Send" name="send">
            </div>
        </form>

       

        
    </div>
    <script>
function sendCode() {
    const email = document.getElementById("email").value;
    const userType = document.querySelector('input[name="userType"]:checked')?.value;

    if (!email || !userType) {
        alert("Please enter email and select user type.");
        return;
    }

    fetch("sendCode.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${encodeURIComponent(email)}&userType=${encodeURIComponent(userType)}`
    })
    .then(response => response.text())
    .then(result => {
        alert(result); // You can customize this
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Failed to send code.");
    });
}
</script>
</body>
</html>
