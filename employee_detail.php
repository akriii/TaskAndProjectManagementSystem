<?php
session_start();
include('config.php');

// Get admin name
$adminName = "Admin";
if (isset($_SESSION['id'])) {
    $adminID = $_SESSION['id'];
    $sql = "SELECT AdminName FROM admin WHERE AdminID = '$adminID'";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $adminName = $row['AdminName'];
    }
}

// Sanitize input
$empID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$status = isset($_GET['status']) && $_GET['status'] === 'Not Completed' ? 'Not Completed' : 'Completed';

// Get employee name
$empName = "Unknown";
$empDept = "";
$empResult = mysqli_query($db, "SELECT EmpName, Department FROM employee WHERE EmpID = $empID");
if ($row = mysqli_fetch_assoc($empResult)) {
    $empName = $row['EmpName'];
    $empDept = $row['Department'];
}

$totalTasks = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM employee_task WHERE EmpID = $empID"))['total'];
$completedTasks = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as completed FROM employee_task WHERE EmpID = $empID AND Status = 'Completed'"))['completed'];
$notCompletedTasks = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as notCompleted FROM employee_task WHERE EmpID = $empID AND Status = 'Not Completed'"))['notCompleted'];

// Get tasks by status
$taskSql = "
    SELECT t.*
    FROM task t
    JOIN employee_task et ON t.TaskID = et.TaskID
    WHERE et.EmpID = $empID AND et.Status = '$status'
";
$taskResult = mysqli_query($db, $taskSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Employee Detail - <?= $empName ?></title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url("images/plufow-le-studio-loq_SHCuEyg-unsplash.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
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
      background: rgba(250, 249, 249, 0.2);
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
      padding: 30px 50px;
      overflow-y: auto;
    }

    .header {
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 10px;
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

    .back-button {
      display: inline-block;
      margin: 10px 0 20px;
      padding: 8px 16px;
      background: #00bcd4;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
    }

   .back-button {
    display: inline-block;
    margin: 10px 0 20px;
    padding: 8px 16px;
    background: #4CAF50; /* Green color */
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background 0.3s;
  }

  .back-button:hover {
    background: #45a049;
  }

  .form-box {
    background: rgba(255,255,255,0.95);
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    width: 100%;
  }

  .form-box div {
    margin-bottom: 10px;
    font-size: 16px;
  }

  .form-box strong {
    display: inline-block;
    width: 140px;
  }

  .stats {
    display: flex;
    gap: 20px;
    font-weight: 500;
  }

  .switcher {
    margin: 20px 0;
    display: flex;
    gap: 20px;
  }

  .switcher a {
    text-decoration: none;
    font-weight: bold;
    color: #4CAF50;
    background: rgba(255,255,255,0.8);
    padding: 8px 14px;
    border-radius: 10px;
    transition: 0.3s;
  }

  .switcher a.active {
    background: #4CAF50;
    color: white;
  }

  table {
      width: 100%;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      overflow: hidden;
      border-collapse: collapse;
    }

    th, td {
      padding: 16px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
    }

    .view-btn {
      text-decoration: none;
      background:rgb(1, 179, 28);
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: bold;
    }

    .card {
      background: rgba(255,255,255,0.95);
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="sidebar">
    <a href="Adminpage.php">📊 Dashboard</a>
    <a href="admin_profile.php">👤 Profile</a>
    <a href="add_task.php">➕ Add Task</a>
    <a href="addEmployee.php">🙍‍♂️ Add New Employee</a>
    <a href="view_task_admin.php">📋 Task List</a>
    <a href="employee_list.php" class="active">👥 Employee List</a>
    <a href="logoutAdmin.php">🚪 Logout</a>
  </div>

  <div class="main">
    <div class="header">Employee Detail</div>
    
    <div class="profile">👤 Admin: <?= $adminName ?></div>

    <div class="info form-box">
      <div><strong>Employee Name:</strong> <?= ($empName) ?></div>
      <div><strong>Department:</strong> <?= ($empDept) ?></div>
      <div class="stats">
        <div><strong>Total: </strong> <?= $totalTasks ?><p></p>
        <div><strong>Completed: </strong> <?= $completedTasks ?></div>
        <div><strong>Not Completed: </strong> <?= $notCompletedTasks ?></div>
      </div>
    </div>
    <div>

    <div class="switcher">
      <a href="employee_detail.php?id=<?= $empID ?>&status=Completed" class="<?= $status === 'Completed' ? 'active' : '' ?>">Completed</a>
      <a href="employee_detail.php?id=<?= $empID ?>&status=Not Completed" class="<?= $status === 'Not Completed' ? 'active' : '' ?>">Not Completed</a>
    </div>

    <div>

    </div>
    <?php if (mysqli_num_rows($taskResult) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Due Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; while ($task = mysqli_fetch_assoc($taskResult)): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= ($task['Title']) ?></td>
              <td><?= ($task['Desc']) ?></td>
              <td><?= ($task['DueDate']) ?></td>
              <td>
                <a href="task_details.php?taskID=<?= $task['TaskID'] ?>" class="view-btn">Details</a>
              </td>

            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="card">No tasks found.</div>
    <?php endif; ?>
  </div>
  <a href="employee_list.php" class="back-button">Back </a>
</div>
</body>
</html>
