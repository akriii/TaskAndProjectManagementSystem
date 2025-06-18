<?php 
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
  die("User not logged in.");
}

$employeeId = $_SESSION['id'];
$employeeName = "Employee";
$nameSql = "SELECT EmpName FROM employee WHERE EmpID = '$employeeId'";
$EmpName = mysqli_query($db,$nameSql);
if($row = mysqli_fetch_assoc($EmpName)){
  $employeeName = $row['EmpName'];
}

$employeeID = $_SESSION['id'];
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

$search = "%{$searchTerm}%";

$sql = "SELECT t.TaskID, t.Title, t.Desc, et.Status, t.DueDate, t.Attachment, et.Comment, e.EmpName
        FROM task t
        JOIN employee_task et ON t.TaskID = et.TaskID
        JOIN employee e ON e.EmpID = et.EmpID
        WHERE e.EmpID = '$employeeID' AND LOWER(et.Status) = 'completed' AND t.Title LIKE '$search'
        ORDER BY t.DueDate DESC";

$result = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Task History</title>
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

    .task-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 25px;
      margin: 15px auto;
      border-radius: 16px;
      width: 85%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: background 0.3s;
    }

    .task-box:hover {
      background-color: #f0f0f0;
    }

    .task-title {
      font-size: 20px;
      font-weight: bold;
      color: #000;
      margin-bottom: 8px;
    }

    .task-date {
      font-size: 16px;
      color: #666;
    }

    form {
      text-align: center;
      margin-bottom: 20px;
    }

    input[type="text"] {
      padding: 10px;
      width: 250px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    button[type="submit"] {
      padding: 10px 16px;
      background-color: #00bcd4;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #0097a7;
    }
    .status-badge {
      padding: 5px 12px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: bold;
      background-color: #e8f5e9;
      color: #2e7d32;
      text-transform: capitalize;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="sidebar">
    <a href="employee_homepage.php">🏠 Homepage</a>
    <a href="employee_profile.php">🙍‍♂️ Profile</a>
    <a href="task_history.php" class="active">📜 Task History</a>
    <a href="logout.php">🚪 Logout</a>
  </div>
  <div class="profile">👋 Employee: <?php echo $employeeName; ?></div>


  <div class="main">
    <div class="header">Completed Task History</div>

    <form method="GET">
      <input type="text" name="search" placeholder="Search tasks..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
      <button type="submit">Search</button>
    </form>

    <?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
    <div class="task-box" onclick="window.location.href='view_task.php?task_id=<?php echo $row['TaskID']; ?>'">
        <div class="task-title">📌 <?php echo $row['Title']; ?></div>
        <div class="task-date">Due: <?php echo $row['DueDate']; ?></div>
        <span class="status-badge"><?php echo $row['Status']; ?></span>
    </div>
<?php
    }
} else {
?>
    <p style="text-align: center;">No completed tasks found.</p>
<?php
}
?>

  </div>
</div>
</body>
</html>
