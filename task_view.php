<?php 
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
    header("Location: indexLogin.php");
    exit();
}
// Check if a task ID is provided
if (!isset($_GET['task_id'])) {
    die("No task selected.");
}

$taskId = $_GET['task_id'];
$employeeID = $_SESSION['id'];

$sql = "SELECT t.TaskID, t.Title, t.Desc, et.Status, t.DueDate, t.Attachment, et.Comment, e.EmpName
        FROM task t
        JOIN employee_task et ON t.TaskID = et.TaskID
        JOIN employee e ON e.EmpID = et.EmpID
        WHERE e.EmpID = '$employeeID' AND t.TaskID = '$taskId'
        LIMIT 1";

$result = mysqli_query($db, $sql);

if (!$result) {
  die("Query failed: " . mysqli_error($db));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Employee View Task</title>
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

    .main-content {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
    }

    .header-title {
      font-size: 32px;
      font-weight: bold;
      text-align: center;
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

    .task-details {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 25px;
      margin: 0 auto;
      border-radius: 16px;
      width: 85%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .detail-row {
      margin-bottom: 20px;
    }

    .detail-label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    textarea,
    input[type="text"] {
      width: 100%;
      padding: 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    textarea[readonly],
    input[readonly] {
      background-color: #f5f5f5;
    }

    a {
      color: #00bcd4;
      font-weight: 500;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
    .action-buttons{
      padding: 10px 20px;
      background-color: #00bcd4;
      border: none;
      border-radius: 6px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      width: 150px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="sidebar">
      <a href="employee_homepage.php" class="active">🏠 Homepage</a>
      <a href="employee_profile.php">🙍‍♂️ Profile</a>
      <a href="task_history.php" >📜 Task History</a>
      <a href="logout.php">🚪 Logout</a>
    </div>

    


  <div class="main-content">
    <div class="header-title">View Task Details</div>
    <?php
      if (mysqli_num_rows($result) > 0):
        $row = mysqli_fetch_assoc($result);
        
    ?><div class="profile">👋 Employee: <?php echo ($row['EmpName']); ?></div>
    

    <div class="task-details">
      <div class="detail-row">
        <div class="detail-label">Title:</div>
        <div class="detail-content">
          <textarea readonly><?php echo ($row['Title']); ?></textarea>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Description:</div>
        <div class="detail-content">
          <textarea readonly><?php echo ($row['Desc']); ?></textarea>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Status:</div>
        <div class="detail-content">
          <input type="text" value="<?php echo ($row['Status']); ?>" readonly>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Due Date:</div>
        <div class="detail-content">
          <input type="text" value="<?php echo ($row['DueDate']); ?>" readonly>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Attachments:</div>
        <div class="detail-content file-attachment">
          <?php if (!empty($row['Attachment'])): ?>
            <a href="<?php echo ($row['Attachment']); ?>" target="_blank">
              <?php echo ($row['Attachment']); ?>
            </a>
          <?php else: ?>
            <p>No attachment</p>
          <?php endif; ?>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Comments by admin:</div>
        <div class="detail-content">
          <textarea readonly><?php echo ($row['Comment']); ?></textarea>
        </div>
      </div>

      
        <a  class="action-buttons" href="upload.php?task_id=<?php echo $row['TaskID']; ?>">Upload Work</a>
      
    </div>
    <?php else: ?>
      <p style="text-align:center;">Task not found or not assigned to you.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
