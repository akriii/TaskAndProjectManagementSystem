<?php 
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
  die("User not logged in.");
}

$employeeId = $_SESSION['id'];

$sql = "SELECT e.EmpName, e.EmpEmail, e.Department
        FROM employee e
        WHERE e.EmpID = '$employeeId'";

$result = mysqli_query($db, $sql);
$employee = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Profile</title>
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
  color: #000; /* ← changed to black */
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
      padding: 40px;
      overflow-y: auto;
    }

    .header {
      font-size: 32px;
      font-weight: bold;
      color: #000;
      text-align: center;
      margin-bottom: 20px;
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

    .form-box input, .form-box select {
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
      background-color: #00bcd4;
      border: none;
      border-radius: 6px;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .form-box button:hover {
      background-color: #0097a7;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <a href="employee_homepage.php">🏠 Homepage</a>
      <a href="employee_profile.php" class="active">🙍‍♂️ Profile</a>
      <a href="task_history.php">📜 Task History</a>
      <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main">
      <div class="header">Update Profile</div>
      <div class="profile">👋 Employee: <?=($employee['EmpName']) ?></div>

      <div class="info-box">
        <p><strong>Name:</strong> <?=($employee['EmpName']) ?></p>
        <p><strong>Email:</strong> <?= ($employee['EmpEmail']) ?></p>
        <p><strong>Department:</strong> <?= ($employee['Department']) ?></p>
      </div>

      <div class="form-box">
        <form method="POST" action="update_employee_profile.php">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" value="<?= $employee['EmpEmail'] ?>" required>

  <label for="username">Username:</label>
  <input type="text" id="username" name="username" value="<?= $employee['EmpName'] ?>" required>

  <label for="password">Password:</label>
  <input type="password" id="password" name="password" placeholder="Enter new password" required minlength="8" required>

          <small id="passwordError" style="color: red; display: none;">Password must be at least 8 characters long.</small>

  <label for="department">Department:</label>
  <select name="department" id="department" required>
    <?php
      $departments = [
        "Information Technology", "Human Resource", "Finance",
        "Marketing", "Production", "Sales", "Research And Development"
      ];
      foreach ($departments as $dept) {
        $selected = ($employee['Department'] === $dept) ? "selected" : "";
        echo "<option value='$dept' $selected>$dept</option>";
      }
    ?>
  </select>

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
