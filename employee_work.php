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

// Check task ID
if (!isset($_GET['taskID']) || empty($_GET['taskID'])) {
    die("Task ID is missing.");
}

$taskID = $_GET['taskID'];
$taskResult = mysqli_query($db, "SELECT * FROM task WHERE TaskID = '$taskID'");
$task = mysqli_fetch_assoc($taskResult);

if (!$task) {
    die("Task not found.");
}

// Get employee ID
$empID = isset($task['EmpID']) ? $task['EmpID'] : null;


if (!$empID) {
    $empRes = mysqli_query($db, "SELECT EmpID FROM employee_task WHERE TaskID = '$taskID'");
    $empRow = mysqli_fetch_assoc($empRes);
    if ($empRow) {
        $empID = $empRow['EmpID'];
    } else {
        die("Employee details not found.");
    }
}

// Get employee task details
$empTaskRes = mysqli_query($db, "SELECT Comment, UploadedTask, Status FROM employee_task WHERE TaskID = '$taskID' AND EmpID = '$empID'");
$employee_task = mysqli_fetch_assoc($empTaskRes);

if (!$employee_task) {
    die("Employee task details not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = mysqli_real_escape_string($db, $_POST['Status']);
    $newComment = mysqli_real_escape_string($db, $_POST['Comment']);

    if (!empty($empID) && !empty($taskID)) {
        $updateQuery = "
            UPDATE employee_task 
            SET Status = '$newStatus', Comment = '$newComment' 
            WHERE TaskID = '$taskID' AND EmpID = '$empID'
        ";
        mysqli_query($db, $updateQuery) or die("Error updating task: " . mysqli_error($db));

        header("Location: task_details.php?taskID=$taskID");
        exit();
    } else {
        die("Missing Employee ID or Task ID.");
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task Details</title>
  <!--<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" /> -->
  <style>
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
      color: #000;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      border-bottom: 2px solid rgba(255, 255, 255, 0.3);
      margin-bottom: 20px;
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


    .task-details {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 25px;
      margin: 20px auto;
      border-radius: 16px;
      width: 85%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .task-details input,
.task-details select,
.task-details textarea {
  width: 100%;
  padding: 14px 16px;
  margin-top: 8px;
  margin-bottom: 20px;
  border-radius: 14px;
  border: 1px solid #ccc;
  font-size: 16px;
  font-family: 'Segoe UI', sans-serif;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: inset 1px 1px 6px rgba(0, 0, 0, 0.05);
  transition: border 0.3s, box-shadow 0.3s;
}

.task-details input:focus,
.task-details select:focus,
.task-details textarea:focus {
  border-color: #00bcd4;
  box-shadow: 0 0 5px rgba(0, 188, 212, 0.4);
  outline: none;
}

    .form-group {
      margin-bottom: 15px;
    }

    .btn {
      padding: 6px 12px;
      background: #333;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      background-color: #4caf50;
    }

    .btn:hover {
      background: #555;
       
    }

    label {
      font-weight: normal;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="sidebar">
    <a href="Adminpage.php">📊 Dashboard</a>
    <a href="admin_profile.php" >👤 Profile</a>
    <a href="add_task.php">➕ Add Task</a>
    <a href="addEmployee.php">🙍‍♂️ Add New Employee</a>
    <a href="view_task_admin.php" class="active">📋 Task List</a>
    <a href="employee_list.php"  >👥 Employee List</a>
    <a href="logoutAdmin.php">🚪 Logout</a>
  </div>

    <div class="main-content">
      <header>
        <div class="header-title">Task Details</div>
         <div class="profile">👤 Admin: <?php echo ($adminName); ?>
</div>
      </header>

      <div class="task-details">
        <form method="post" action="">
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Uploaded Task:</label>
            <div class="col-sm-10">
              <?php if (!empty($employee_task['UploadedTask'])): ?>
                <a href="<?php echo ($employee_task['UploadedTask']); ?>" target="_blank">View File</a>
              <?php else: ?>
                <p>No file uploaded.</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="form-group row">
            <label for="Comment" class="col-sm-2 col-form-label">Comment:</label>
            <div class="col-sm-10">
              <textarea name="Comment" class="form-control" rows="3"><?php echo ($employee_task['Comment']); ?></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label for="Status" class="col-sm-2 col-form-label">Task Status:</label>
            <div class="col-sm-10">
              <select name="Status" class="form-control" required>
                <option value="Completed" <?php if ($employee_task['Status'] === 'Completed') echo 'selected'; ?>>Completed</option>
                <option value="Not Completed" <?php if ($employee_task['Status'] === 'Not Completed') echo 'selected'; ?>>Not Completed</option>
              </select>
            </div>
          </div>

          <div class="btn-space">
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
