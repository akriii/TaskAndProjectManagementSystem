<?php
session_start();
include('config.php');

// Get admin name
$adminName = "Admin";
if (isset($_SESSION['id'])) {
    $adminID = $_SESSION['id'];
    $result = mysqli_query($db, "SELECT AdminName FROM admin WHERE AdminID = '$adminID'");
    if ($row = mysqli_fetch_assoc($result)) {
        $adminName = $row['AdminName'];
    }
}

// Check for taskID
if (!isset($_GET['taskID']) || empty($_GET['taskID'])) {
    die("Task ID is missing.");
}

$taskID = $_GET['taskID'];

// Get task details
$taskResult = mysqli_query($db, "SELECT * FROM task WHERE TaskID = '$taskID'");
$task = mysqli_fetch_assoc($taskResult);
if (!$task) {
    die("Task not found.");
}

// Get employee-task data
$sql = "SELECT e.EmpID, e.EmpName, e.EmpEmail, et.Comment, et.UploadedTask, et.Status
        FROM employee_task et
        JOIN employee e ON et.EmpID = e.EmpID
        WHERE et.TaskID = '$taskID'";
$employeeResult = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task & Employee Details</title>
  <!--<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" /> -->
  <style>
  * {
    box-sizing: border-box;
  }

  body, label, input, textarea, select, option, span, div, th, tr, td {
    color: black !important;
  }

  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-image: url("images/plufow-le-studio-loq_SHCuEyg-unsplash.jpg");
    background-size: cover;
    background-attachment: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
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

  .main-content {
    flex: 1;
    padding: 30px;
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
    color: #fff;
  }

  .profile {
    color: #000;
    font-weight: bold;
    background: rgba(0, 188, 212, 0.2);
    padding: 8px 16px;
    border-radius: 20px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    font-weight: bold;
    color: #fff;
  }

  .form-group input,
  .form-group textarea,
  .form-group select {
    width: 95%;
    padding: 10px;
    border-radius: 10px;
    border: none;
    background: rgba(255, 255, 255, 0.8);
    box-shadow: inset 1px 1px 5px rgba(0,0,0,0.1);
  }

  .task-details, .employee-table {
   background-color: rgba(255, 255, 255, 0.95);
      padding: 25px;
      margin: 20px auto;
      border-radius: 16px;
      width: 85%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

  .btn-sm {
    padding: 6px 12px;
    font-size: 14px;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
  }

  .btn-primary {
    background-color: #4cc5ff;
    color: white;
    border: none;
    transition: 0.3s;
  }

  .btn-primary:hover {
    background-color: #00bcd4;
  }

  .btn-danger {
    background-color: #ff6b6b;
    color: white;
    border: none;
    transition: 0.3s;
  }

  .btn-danger:hover {
    background-color: #ff1e1e;
  }

  .btn {
    padding: 10px 20px;
    font-size: 15px;
    border-radius: 10px;
    text-align: center;
    font-weight: bold;
    color: white;
  }

  .d-flex {
    display: flex;
  }

  .justify-content-end {
    justify-content: flex-end;
  }

  .mt-3 {
    margin-top: 1rem;
  }
   .back-button {
   
   
    padding: 10px 20px;
    background: #4CAF50; /* Green color */
    color: white;
    
    border-radius: 8px;
    font-weight: bold;
    transition: background 0.3s;
    margin-right: 83%;
    margin-top: 2%;
    width: 100px;
  }

  .back-button:hover {
    background: #45a049;
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
      <a href="view_task_admin.php" class="active">📋 Task List</a>
      <a href="employee_list.php">👥 Employee List</a>
      <a href="logoutAdmin.php">🚪 Logout</a>
    </div>
    <div class="main-content">
      <header>
        <div class="header-title">Task & Employee Details</div>
        <div class="profile">👤 Admin: <?php echo htmlspecialchars($adminName); ?></div>
      </header>

      <div class="task-details">
        
        <div class="form-group">
          <label>Title:</label><br>
          <input type="text" class="form-control" value="<?php echo htmlspecialchars($task['Title']); ?>" disabled>
        </div>
        <div class="form-group"><br>
          <label>Description:</label>
          <textarea class="form-control" rows="3" disabled><?php echo htmlspecialchars($task['Desc']); ?></textarea>
        </div>
        <div class="form-group">
          <label>Due Date:</label><br>
          <input type="date" class="form-control" value="<?php echo $task['DueDate']; ?>" disabled>
        </div>
        <div class="form-group">
          <label>Attachment:</label><br>
          <?php if (!empty($task['Attachment'])): ?>
            <a href="<?php echo $task['Attachment']; ?>" target="_blank"><?php echo $task['Attachment']; ?></a>
          <?php else: ?>
            <span>No attachments uploaded.</span>
          <?php endif; ?>
        </div>
        <div class="d-flex justify-content-end">
          <button onclick="history.back()" class="back-button">Back</button>
            <form action="delete_task.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this task? This cannot be undone.');">
            <input type="hidden" name="TaskID" value="<?php echo $taskID; ?>">
            <button type="submit" class="btn btn-danger mt-3">Delete</button>
            </form>
            
        </div>

      </div>

      <div class="employee-table">
        <h4>Assigned Employees</h4>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Emp ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Uploaded Task</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($employeeResult->num_rows > 0): ?>
              <?php while ($row = $employeeResult->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $row['EmpID']; ?></td>
                  <td><?php echo htmlspecialchars($row['EmpName']); ?></td>
                  <td><?php echo htmlspecialchars($row['EmpEmail']); ?></td>
                  <td><?php echo htmlspecialchars($row['Status']); ?></td>
                  <td>
                    <?php if (!empty($row['UploadedTask'])): ?>
                      <a href="<?php echo $row['UploadedTask']; ?>" target="_blank" style="color:#00f;">View</a>
                    <?php else: ?>
                      No file
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="employee_work.php?taskID=<?php echo $taskID; ?>&empID=<?php echo $row['EmpID']; ?>" class="btn btn-primary btn-sm">View Work</a>
                    <a href="remove_employee_task.php?taskID=<?php echo $taskID; ?>&empID=<?php echo $row['EmpID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this employee from the task?');">Remove</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6">No employees assigned to this task.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
