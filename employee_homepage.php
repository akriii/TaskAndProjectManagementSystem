<?php 
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
  die("User not logged in.");
}
$employeeId = $_SESSION['id'];
$nameResult = mysqli_query($db, "SELECT EmpName FROM employee WHERE EmpID = '$employeeId'");
$employee = mysqli_fetch_assoc($nameResult);


$sql = "SELECT t.TaskID, t.Title, et.Status, e.EmpName
        FROM task t
        JOIN employee_task et ON t.TaskID = et.TaskID
        JOIN employee e ON e.EmpID = et.EmpID
        WHERE e.EmpID = '$employeeId' AND LOWER(et.Status) != 'completed'";

$result = mysqli_query($db, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Homepage</title>
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
      padding: 30px 50px;
      overflow-y: auto;
    }

    .header {
      text-align: center;
      font-size: 32px;
      font-weight: bold;
      color: #000;
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

    .task-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 20px;
      margin: 20px auto;
      border-radius: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 85%;
      transition: transform 0.2s ease;
    }

    .task-box:hover {
      transform: scale(1.02);
    }

    .task-info {
      font-size: 18px;
      font-weight: 600;
    }

    .task-actions {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    button {
      padding: 8px 16px;
      background-color: #00bcd4;
      border: none;
      border-radius: 6px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background-color: #0097a7;
    }

    .status-badge {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: bold;
      text-transform: capitalize;
    }

    .status-completed {
      background-color: #e8f5e9;
      color: #2e7d32;
    }

    .status-not-completed {
      background-color: #ffebee;
      color: #c62828;
    }

    p {
      color: #fff;
      font-size: 18px;
      text-align: center;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="sidebar">
    <a href="employee_homepage.php" class="active">🏠 Homepage</a>
    <a href="employee_profile.php">🙍‍♂️ Profile</a>
    <a href="task_history.php">📜 Task History</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

<div class="profile">👋 Employee: <?= $employee['EmpName'] ?></div>

  <div class="main">
    <div class="header">Dashboard</div>

    <?php if ($result->num_rows > 0): 
      $rowDisplayed = false;
      while ($row = $result->fetch_assoc()): // retrieve one row of data at a time and returns data as an ARRAY
        
        if (!$rowDisplayed): ?>  
          
        <?php 
          $rowDisplayed = true;
        endif;

        $status = strtolower($row['Status']);
        $badgeClass = ($status === 'completed') ? 'status-completed' : 'status-not-completed';
        /* (same as write it manually)
        if ($status === 'completed') {
        $badgeClass = 'status-completed';
      } else {
        $badgeClass = 'status-not-completed';
      }
        */
    ?>
      <div class="task-box">
        <div class="task-info"><?=($row['Title']); ?></div>
        <div class="task-actions">
          <button onclick="window.location.href='task_view.php?task_id=<?= $row['TaskID']; ?>'">View</button>
          <span class="status-badge <?= $badgeClass ?>"><?= ($row['Status']); ?></span>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p>🎉 You have no pending tasks!</p>
    <?php endif; ?> 
  </div>
</div>
</body>
</html>
