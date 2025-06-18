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

// Fetch department distribution
$departments = [];
$deptResult = mysqli_query($db, "SELECT Department, COUNT(*) as Total FROM employee GROUP BY Department");
while ($row = mysqli_fetch_assoc($deptResult)) {
    $departments[] = $row;
}

// Fetch employee task completion progress grouped by department
$progress = [];
$sql = "
    SELECT e.EmpName, e.Department,
           COUNT(et.TaskID) AS TotalTasks,
           SUM(CASE WHEN et.Status = 'Completed' THEN 1 ELSE 0 END) AS CompletedTasks
    FROM employee e
    LEFT JOIN employee_task et ON e.EmpID = et.EmpID
    GROUP BY e.EmpID
";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_assoc($result)) {
   if ($row['TotalTasks'] > 0) {
    $percentage = round(($row['CompletedTasks'] / $row['TotalTasks']) * 100);
} else {
    $percentage = 0;
}
    $progress[$row['Department']][] = [
        'EmpName' => $row['EmpName'],
        'Percentage' => $percentage
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      text-align: center;
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 30px;
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

    .chart-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
     
    }

    .progress-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }

    .dept-card {
      background: rgba(255, 255, 255, 0.95);
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .dept-card h5 {
      margin-bottom: 10px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
    }

    .progress-label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .progress {
      height: 20px;
      background-color: #f1f1f1;
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 15px;
    }

    .progress-bar {
      height: 100%;
      background-color: #4caf50;
      color: white;
      font-size: 12px;
      line-height: 20px;
      text-align: center;
    }

    .chart-wrapper {
      width: 500px;
      height: 500px;
      margin: 0 auto;
    }

    canvas {
      width: 100% !important;
      height: 100% !important;
    }
    
  </style>
</head>
<body>
<div class="container">
  <div class="sidebar">
    <a href="Adminpage.php" class="active">📊 Dashboard</a>
    <a href="admin_profile.php">👤 Profile</a>
    <a href="add_task.php">➕ Add Task</a>
    <a href="addEmployee.php">🙍‍♂️ Add New Employee</a>
    <a href="view_task_admin.php">📋 Task List</a>
     <a href="employee_list.php" >👥 Employee List</a>
    <a href="logoutAdmin.php">🚪 Logout</a>
  </div>

  <div class="main">
    <div class="header">Welcome, <?= ($adminName) ?></div>
    <div class="profile">👤 Admin: <?= ($adminName) ?></div>

    <div class="chart-box">
      <h4>Department Distribution</h4>
      <div class="chart-wrapper">
        <canvas id="departmentChart"></canvas>
      </div>
    </div>

    <div class="chart-box">
      <h4>Employee Task Completion</h4>
      <div class="progress-grid">
        <?php foreach ($progress as $department => $employees): ?>
          <div class="dept-card">
            <h5><?= ($department) ?></h5>
            <?php foreach ($employees as $emp): ?>
              <div class="progress-label"><?=($emp['EmpName']) ?> (<?= $emp['Percentage'] ?>%)</div>
              <div class="progress">
                <div class="progress-bar" style="width: <?= $emp['Percentage'] ?>%;"><?= $emp['Percentage'] ?>%</div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<script>
  const ctx = document.getElementById('departmentChart').getContext('2d');
  const departmentChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: <?= json_encode(array_column($departments, 'Department')) ?>,
      datasets: [{
        data: <?= json_encode(array_column($departments, 'Total')) ?>,
        backgroundColor: ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949', '#af7aa1', '#ff9da7'],
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'right'
        }
      }
    }
  });
</script>
</body>
</html>
