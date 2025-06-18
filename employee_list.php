<?php
session_start();
include("config.php");

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';


// Get all departments
$departments = [];
$result = mysqli_query($db, "SELECT DISTINCT Department FROM employee");
while ($row = mysqli_fetch_assoc($result)) {
    $departments[] = $row['Department'];
}

// Fetch filtered employees
$query = "SELECT * FROM employee WHERE 1";
$params = [];

if (!empty($searchTerm)) {
    $query .= " AND EmpName LIKE ?";
    $params[] = "%$searchTerm%";
}
if (!empty($departmentFilter)) {
    $query .= " AND Department = ?";
    $params[] = $departmentFilter;
}

$stmt = $db->prepare($query);
if ($params) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$employees = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Employee List</title>
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
      color: #000;
    }

    h2 {
      font-size: 28px;
      margin-bottom: 30px;
      text-align: center;
    }

    form {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 25px;
    }

    input, select {
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ccc;
    }

    button {
      background: #4caf50;rgb(117, 220, 121);  
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background: #0097a7;
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

    .progress-container {
      background: #f1f1f1;
      border-radius: 10px;
      overflow: hidden;
    }

    .progress-bar {
      height: 18px;
      background-color: #4caf50;
      color: white;
      font-size: 12px;
      text-align: center;
      border-radius: 10px;
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
    <a href="test.php" class="active">👥 Employee List</a>
    <a href="logoutAdmin.php">🚪 Logout</a>
  </div>

  <div class="main">
    <h2>Employee List</h2>

    <form method="GET">
      <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($searchTerm) ?>">
      <select name="department">
        <option value="">All Departments</option>
        <?php foreach ($departments as $dept): ?>
          <option value="<?= $dept ?>" <?= $dept == $departmentFilter ? 'selected' : '' ?>><?= $dept ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Filter</button>
    </form>

    <table>
      <tr>
        <th>Name</th>
        <th>Department</th>
        <th>Progress</th>
        <th>Action</th>
      </tr>
      <?php while ($row = $employees->fetch_assoc()): ?>
        <?php
          // Get task progress for each employee
          $empId = $row['EmpID'];
          $taskRes = mysqli_query($db, "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN Status = 'Completed' THEN 1 ELSE 0 END) as done 
                        FROM employee_task WHERE EmpID = '$empId'");
          $task = mysqli_fetch_assoc($taskRes);
          $total = $task['total'];
          $done = $task['done'];
          $percentage = $total > 0 ? round(($done / $total) * 100) : 0;
        ?>
        <tr>
          <td><?= htmlspecialchars($row['EmpName']) ?></td>
          <td><?= htmlspecialchars($row['Department']) ?></td>
          <td>
            <div class="progress-container">
              <div class="progress-bar" style="width: <?= $percentage ?>%;"><?= $percentage ?>%</div>
            </div>
          </td>
          <td ><a class="btn" href="employee_detail.php?id=<?= $row['EmpID'] ?>">View Details</a></td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
</div>
</body>
</html>
