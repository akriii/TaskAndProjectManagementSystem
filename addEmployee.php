<?php
session_start();
include('config.php');

if (!isset($_SESSION['id'])) {
    echo "<script>alert('Admin not logged in.'); window.location.href='index.html';</script>";
    exit;
}

$adminID = $_SESSION['id'];
$adminName = "Admin";

$result = mysqli_query($db, "SELECT AdminName FROM admin WHERE AdminID = '$adminID'");
if ($row = mysqli_fetch_assoc($result)) {
    $adminName = $row['AdminName'];
}

$empQuery = mysqli_query($db, "SELECT EmpID, EmpName, Department FROM employee");
$employeeData = [];
while ($row = mysqli_fetch_assoc($empQuery)) {
    $employeeData[$row['Department']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add New Employee</title>
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

  

    .main-content {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
     
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

    .form-container {
 background-color: rgba(255, 255, 255, 0.95);
      padding: 25px;
      margin: 20px auto;
      border-radius: 16px;
      width: 85%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}


    

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .form-control {
      width: 95%;
      padding: 12px;
      border-radius: 12px;
      border: 1px solid #ccc;
      background: rgba(255, 255, 255, 0.8);
      box-shadow: inset 1px 1px 4px rgba(0,0,0,0.05);
    }

    .btn-submit {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      background-color:#4caf50;
      color: white;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-submit:hover {
      background-color::rgb(67, 148, 70);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="sidebar">
    <a href="Adminpage.php">📊 Dashboard</a>
    <a href="admin_profile.php">👤 Profile</a>
    <a href="add_task.php">➕ Add Task</a>
    <a href="addEmployee.html" class="active">🙍‍♂️ Add New Employee</a>
    <a href="view_task_admin.php">📋 Task List</a>
     <a href="employee_list.php" >👥 Employee List</a>
    <a href="logoutAdmin.php">🚪 Logout</a>
  </div>

  <div class="main-content">
    <header>
      <div class="header-title">Add New Employee</div>
      <div class="profile">👤 Admin: <?php echo ($adminName); ?></div>
    </header>

    <div class="form-container">
      

      <form method="post" action="insertData.php">
        <input type="hidden" name="userType" value="employee">

        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" class="form-control" name="email" id="email" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" name="username" id="username" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" name="password" id="password" autocomplete="off" required minlength="8">
          <small id="passwordError" style="color: red; display: none;">Password must be at least 8 characters long.</small>

        </div>

        <div class="form-group">
          <label for="department">Department</label>
          <select class="form-control" name="department" id="department" required>
            <option value="Information Technology">Information Technology</option>
            <option value="Human Resource">Human Resource</option>
            <option value="Finance">Finance</option>
            <option value="Marketing">Marketing</option>
            <option value="Production">Production</option>
            <option value="Sales">Sales</option>
            <option value="Research And Development">Research And Development</option>
          </select>
        </div>

        <div class="form-group">
          <button type="submit" class="btn-submit" name="send">Create Employee</button>
        </div>
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
