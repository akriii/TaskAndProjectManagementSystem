<?php 
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
  die("User not logged in.");
}

$employeeId = $_SESSION['id'];

$sql = "SELECT t.Title, t.Desc, t.Status, t.DueDate, et.Comment
        FROM task t
        JOIN employee_task et ON t.TaskID = et.TaskID
        JOIN employee e ON e.EmpID = et.EmpID
        WHERE e.EmpEmail = '" . $_SESSION['id'] . "'";
$result = mysqli_query($db, $sql);
if (!$result) {
  die("Query failed: " . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>View Task</title>
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
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
      overflow: hidden;
    }

    .sidebar {
      width: 220px;
      background-color: #ffffff;
      padding-top: 40px;
      border-right: 1px solid #ccc;
      display: flex;
      flex-direction: column;
    }

    .sidebar a {
      display: block;
      padding: 15px 20px;
      text-decoration: none;
      color: #333;
      border-bottom: 1px solid #ddd;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .sidebar a:hover {
      background-color: #f0f0f0;
      padding-left: 25px;
    }

    .active {
      background-color: #e0f7fa;
      font-weight: bold;
      border-left: 4px solid #4cc5ff;
    }

    .main-content {
      flex: 1;
      padding: 30px;
      background-color: #e9e9e9;
      position: relative;
      overflow-y: auto;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 15px;
      border-bottom: 2px solid #ccc;
      margin-bottom: 30px;
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
      background-color: #333;
      object-fit: cover;
      border: 2px solid #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .task-details {
      background-color: white;
      border-radius: 8px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      max-width: 800px;
      margin: 0 auto;
    }

    .detail-row {
      display: flex;
      margin-bottom: 20px;
    }

    .detail-label {
      font-weight: bold;
      width: 120px;
      flex-shrink: 0;
    }

    .detail-content {
      flex-grow: 1;
    }

    textarea,
    input[type="text"] {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-family: Arial, sans-serif;
    }

    textarea[readonly],
    input[readonly] {
      background-color: #f5f5f5;
    }

    .action-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 30px;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      font-size: 16px;
      text-decoration: none;
      text-align: center;
    }

    .btn-primary {
      background-color: #33b5e5;
      color: white;
    }

    .btn-primary:hover {
      background-color: #0288d1;
    }

    .btn-success {
      background-color: #4CAF50;
      color: white;
    }

    .btn-success:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>
  <div class="container">
    <nav class="sidebar">
      <a href="employee_homepage.php">Homepage</a>
      <a href="employee_profile.php">Profile</a>
      <a href="task_history.php">Task History</a>
      <a href="logout.html">Logout</a>
    </nav>

    <main class="main-content">
      <header>
        <div class="header-title" id="taskTitle">View Task Details</div>
        <div class="profile">
          <img src="images/profile-icon.png" alt="Profile" class="avatar" />
          <span>Employee 1</span>
        </div>
      </header>

      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <div class="task-details">
            <div class="detail-row">
              <div class="detail-label">Title:</div>
              <div class="detail-content">
                <textarea readonly><?php echo htmlspecialchars($row['Title']); ?></textarea>
              </div>
            </div>

            <div class="detail-row">
              <div class="detail-label">Description:</div>
              <div class="detail-content">
                <textarea readonly><?php echo htmlspecialchars($row['Desc']); ?></textarea>
              </div>
            </div>

            <div class="detail-row">
              <div class="detail-label">Status:</div>
              <div class="detail-content">
                <input type="text" value="<?php echo htmlspecialchars($row['Status']); ?>" readonly>
              </div>
            </div>

            <div class="detail-row">
              <div class="detail-label">Due Date:</div>
              <div class="detail-content">
                <input type="text" value="<?php echo htmlspecialchars($row['DueDate']); ?>" readonly>
              </div>
            </div>

            <div class="action-buttons">
              <button class="btn btn-success" onclick="markAsComplete()">Mark as Complete</button>
              <button class="btn btn-primary" onclick="saveChanges()">Save Changes</button>
            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </main>
  </div>

  <script>
    function markAsComplete() {
      alert('Task marked as complete!');
    }

    function saveChanges() {
      alert('Changes saved successfully!');
    }

    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('taskTitle').textContent = 'Task: Complete Project Proposal';
    });
  </script>
</body>
</html>
