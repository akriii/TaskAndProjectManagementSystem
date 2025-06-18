<?php 
session_start();
include("config.php");

// Check if admin is logged in
if (!isset($_SESSION['id'])) {
  die("Admin not logged in.");
}

$adminId = $_SESSION['id'];

// Retrieve admin data
$sql = "SELECT AdminName, AdminEmail FROM admin WHERE AdminID = '$adminId'";
$result = mysqli_query($db, $sql);
$admin = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Update Profile</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url("images/plufow-le-studio-loq_SHCuEyg-unsplash.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #333;
    }

    .container {
      display: flex;
      width: 90%;
      height: 90%;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
    }

    .sidebar {
      width: 240px;
      background: rgba(255, 255, 255, 0.2);
      padding-top: 40px;
      border-right: 1px solid rgba(255,255,255,0.3);
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .sidebar a {
      padding: 15px 25px;
      text-decoration: none;
      color: #000;
      font-weight: 600;
      transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: rgba(255, 255, 255, 0.3);
      border-left: 5px solid #00bcd4;
    }

    .main {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
      color: #000;
    }

   header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      border-bottom: 2px solid rgba(255, 255, 255, 0.3);
      padding-bottom: 10px;
    }

    .header-title {
      font-size: 24px;
      font-weight: bold;
      color: #000;
    }

    .profile {
      position: absolute;
      top: 30px;
      right: 50px;
      color: #000;
      font-weight: bold;
      background: rgba(0, 188, 212, 0.2);
      padding: 8px 16px;
      border-radius: 20px;
    }

    .info-box, .form-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 25px;
      margin: 20px auto;
      border-radius: 16px;
      width: 85%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .info-box p {
      font-size: 18px;
      margin: 10px 0;
    }

    .form-box input {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .form-box button {
      padding: 10px 20px;
      background-color: #28a745;
      border: none;
      border-radius: 6px;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .form-box button:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <a href="Adminpage.php">📊 Dashboard</a>
      <a href="admin_profile.php" class="active">👤 Profile</a>
      <a href="add_task.php">➕ Add Task</a>
      <a href="addEmployee.php">🙍‍♂️ Add New Employee</a>
      <a href="view_task_admin.php">📋 Task List</a>
       <a href="employee_list.php" >👥 Employee List</a>
      <a href="logoutAdmin.php">🚪 Logout</a>
    </div>

    <div class="main">
      <header>
        <div class="header-title">Update Profile</div>
      <div class="profile">👤 Admin: <?= ($admin['AdminName']) ?></div>
      </header>
      

      <div class="info-box">
        <p><strong>Name:</strong> <?= ($admin['AdminName']) ?></p>
        <p><strong>Email:</strong> <?= ($admin['AdminEmail']) ?></p>
      </div>

      <div class="form-box">
        <form method="POST" action="update_admin_profile.php">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" value="<?= $admin['AdminEmail'] ?>" required>

  <label for="username">Username:</label>
  <input type="text" id="username" name="username" value="<?= $admin['AdminName'] ?>" required>

  <label for="password">Password:</label>
   <input type="password" id="password" name="password" placeholder="Enter new password" required minlength="8" required>

          <small id="passwordError" style="color: red; display: none;">Password must be at least 8 characters long.</small>

  <button type="submit" name="send">Save</button>
</form>
      </div>
    </div>
  </div>
  <script>
  const passwordInput = document.getElementById('password');
  const passwordError = document.getElementById('passwordError');
  const form = document.querySelector('form');

  passwordInput.addEventListener('input', function () {
    if (passwordInput.value.length < 8) {
      passwordError.style.display = 'block';
    } else {
      passwordError.style.display = 'none';
    }
  });

  form.addEventListener('submit', function (e) {
    if (passwordInput.value.length < 8) {
      e.preventDefault();
      passwordError.style.display = 'block';
    }
  });
</script>
</body>
</html>
