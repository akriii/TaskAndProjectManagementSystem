<?php
session_start();
include('config.php');

// Get admin name from session
$adminName = "Admin"; // fallback
if (isset($_SESSION['admin_id'])) {
    $adminID = $_SESSION['admin_id'];
    $stmt = $db->prepare("SELECT AdminName FROM admin WHERE AdminID = ?");
    $stmt->bind_param("i", $adminID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $adminName = $row['AdminName'];
    }
}

// Fetch department distribution
$departments = [];
$deptStmt = $db->prepare("SELECT Department, COUNT(*) as Total FROM employee GROUP BY Department");
$deptStmt->execute();
$deptResult = $deptStmt->get_result();
while ($row = $deptResult->fetch_assoc()) {
    $departments[] = $row;
}

// Fetch employee task completion progress and group by department
$progress = [];
$progStmt = $db->prepare("
    SELECT e.EmpName, e.Department,
           COUNT(et.TaskID) AS TotalTasks,
           SUM(CASE WHEN et.Status = 'Completed' THEN 1 ELSE 0 END) AS CompletedTasks
    FROM employee e
    LEFT JOIN employee_task et ON e.EmpID = et.EmpID
    GROUP BY e.EmpID
");
$progStmt->execute();
$progResult = $progStmt->get_result();
while ($row = $progResult->fetch_assoc()) {
    $percentage = $row['TotalTasks'] > 0 ? round(($row['CompletedTasks'] / $row['TotalTasks']) * 100) : 0;
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
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-image: url("images/plufow-le-studio-loq_SHCuEyg-unsplash.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      display: flex;
      width: 90%;
      max-width: 1200px;
      height: 85%;
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
      overflow: hidden;
    }

    .sidebar {
      width: 220px;
      background-color: #fff;
      padding-top: 40px;
      border-right: 1px solid #ccc;
    }

    .sidebar a {
      display: block;
      padding: 15px 20px;
      text-decoration: none;
      color: black;
      border-bottom: 1px solid #ccc;
      cursor: pointer;
    }

    .sidebar a:hover {
      background-color: #eee;
    }

    .active {
      background-color: #e0f7fa;
      font-weight: bold;
      border-left: 4px solid #4cc5ff;
    }

    .main-content {
      flex: 1;
      padding: 20px 40px;
      background-color: #e9e9e9;
      overflow-y: auto;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
      border-bottom: 2px solid #ccc;
      margin-bottom: 20px;
    }

    .header-title {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .chart-container {
      max-width: 300px;
      margin: 0 auto;
    }

    .chart-wrapper {
      background: white;
      padding: 30px;
      border-radius: 12px;
      margin-bottom: 40px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 100%;
    }

    .progress-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }

    .dept-card {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .dept-card h5 {
      margin-bottom: 15px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
    }

    .progress {
      height: 20px;
      background-color: #f1f1f1;
    }

    .progress-bar {
      font-size: 12px;
      line-height: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <a href="Adminpage.php" class="active">Dashboard</a>
      <a href="admin_profile.php">Profile</a>
      <a href="add_task.php">Add Task</a>
      <a href="view_task_admin.php">Task List</a>
      <a href="logoutAdmin.php">Logout</a>
    </div>

    <div class="main-content">
      <header>
        <h2>Welcome, <?php echo htmlspecialchars($adminName); ?></h2>
        <img src="images/profile-icon.png" alt="Profile" class="avatar" />
      </header>

      <!-- Department Chart Section -->
      <h3>Department Distribution</h3>
      <div class="chart-wrapper">
        <div class="chart-container">
          <canvas id="departmentChart" width="300" height="300"></canvas>
        </div>
      </div>

      <!-- Employee Progress Cards -->
      <h4>Employee Task Completion</h4>
      <div class="progress-grid">
        <?php foreach ($progress as $department => $employees): ?>
          <div class="dept-card">
            <h5><?= htmlspecialchars($department) ?></h5>
            <?php foreach ($employees as $emp): ?>
              <div class="mb-3">
                <strong><?= htmlspecialchars($emp['EmpName']) ?> (<?= $emp['Percentage'] ?>%)</strong>
                <div class="progress">
                  <div class="progress-bar bg-success" role="progressbar"
                    style="width: <?= $emp['Percentage'] ?>%;" aria-valuenow="<?= $emp['Percentage'] ?>"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('departmentChart').getContext('2d');
    const departmentChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: <?php echo json_encode(array_column($departments, 'Department')); ?>,
        datasets: [{
          data: <?php echo json_encode(array_column($departments, 'Total')); ?>,
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
