<?php
session_start();
include('config.php');

if (!isset($_SESSION['id'])) {
    echo "<script>alert('Admin not logged in.'); window.location.href='index.html';</script>";
    exit;
}

$adminID = $_SESSION['id'];
$adminName = "Admin";

// Get admin name
$result = mysqli_query($db, "SELECT AdminName FROM admin WHERE AdminID = '$adminID'");
if ($row = mysqli_fetch_assoc($result)) {
    $adminName = $row['AdminName'];
}

// Get employees grouped by department
$empQuery = mysqli_query($db, "SELECT EmpID, EmpName, Department FROM employee");
$employeeData = [];
while ($row = mysqli_fetch_assoc($empQuery)) {
    $employeeData[$row['Department']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Task</title>
  <!--<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" /> -->
  <style>
    body, h1, h2, h3, h4, h5, h6, label, input, textarea, select, option,  a, span, div {
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
      color: #000;
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
      position: absolute;
      top: 30px;
      right: 50px;
      color: #000;
      font-weight: bold;
      background: rgba(0, 188, 212, 0.2);
      padding: 8px 16px;
      border-radius: 20px;
    }
    .form-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 25px;
      margin: 20px auto;
      border-radius: 16px;
      width: 85%;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }


    
    .form-box input {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .form-box button {
      padding: 10px 20px;
      background-color: #28a745;
      border: none;
      border-radius: 6px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
    }

    .form-box button:hover {
      background-color: #218838;
    }

   

    form .form-group {
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

    button {
      padding: 10px 25px;
      border: none;
      background-color: #00bcd4;
      color: white;
      border-radius: 10px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0097a7;
    }

    .upload-area {
      border: 2px dashed #fff;
      padding: 30px;
      text-align: center;
      border-radius: 10px;
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    .upload-icon {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .file-input-wrapper input[type="file"] {
      margin-top: 10px;
    }

    .file-list .file-item {
      display: flex;
      justify-content: space-between;
      background-color: rgba(255,255,255,0.8);
      padding: 8px 12px;
      margin-top: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .submit-wrapper {
      display: flex;
      justify-content: flex-end;
      margin-top: 30px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="sidebar">
    <a href="Adminpage.php">📊 Dashboard</a>
    <a href="admin_profile.php" >👤 Profile</a>
    <a href="add_task.php"class="active">➕ Add Task</a>
    <a href="addEmployee.php">🙍‍♂️ Add New Employee</a>
    <a href="view_task_admin.php" >📋 Task List</a>
     <a href="employee_list.php" >👥 Employee List</a>
    <a href="logoutAdmin.php">🚪 Logout</a>
  </div>

  <div class="main-content">
    <header>
      <div class="header-title">Add New Task</div>
      
        
         <div class="profile">👤 Admin: <?php echo ($adminName); ?>
</div>
      
    </header>
<div class="form-box" style="
  background-color: rgba(255, 255, 255, 0.95);
  padding: 25px;
  margin: 20px auto;
  border-radius: 16px;
  width: 85%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">

  <form action="Adding_process.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="title">Task Title</label><p></p>
      <input type="text" id="title" name="title" required>
    </div>

    <div class="form-group">
      <label for="desc">Description</label><p></p>
      <textarea id="desc" name="desc" rows="4" required></textarea>
    </div>

    <div class="row">
      <div class="col-md-6 form-group">
        <label for="due_date">Due Date</label><p></p>
        <input type="date" id="due_date" name="due_date" required>
      </div>
      <div class="col-md-6 form-group">
        <label for="status">Status</label><p></p>
        <select id="status" name="status" required>
          <option value="Not Completed">Not Completed</option>
          <option value="Completed">Completed</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="department">Department</label><p></p>
      <select id="department" name="department" required>
        <option value="">-- Select Department --</option>
        <?php foreach ($employeeData as $dept => $emps): ?>
          <option value="<?= $dept ?>"><?= $dept ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Assign Employees</label>
      <div id="employeeContainer"></div>
      <button type="button" onclick="addEmployeeDropdown()" class="button">+ Add Employee</button>
    </div>

    <div class="form-group">
      <label>Attachment</label>
      <div class="upload-area" id="uploadArea">
        <div class="upload-icon">📁</div>
        <h5>Drag & Drop Files Here</h5>
        <p>or</p>
        <div class="file-input-wrapper">
          <input type="file" name="attachment[]" multiple class="fileInput">
        </div>
        <p>Maximum file size: 10MB</p>
      </div>
      <div class="file-list" id="fileList"></div>
    </div>

    <div class="submit-wrapper" style="text-align: right;">
      <button type="submit" name="submit">Submit Task</button>
    </div>
  </form>
  
</div>

  </div>
</div>

<script>
const allEmployees = <?= json_encode($employeeData) ?>;
const departmentSelect = document.getElementById('department');
const employeeContainer = document.getElementById('employeeContainer');

function createEmployeeDropdown(dept) {
  const wrapper = document.createElement('div');
  wrapper.className = 'mb-2';

  const select = document.createElement('select');
  select.className = 'form-control';
  select.name = 'employee_id[]';
  select.required = true;

  const defaultOpt = document.createElement('option');
  defaultOpt.value = '';
  defaultOpt.textContent = '-- Select Employee --';
  select.appendChild(defaultOpt);

  if (dept in allEmployees) {
    allEmployees[dept].forEach(emp => {
      const opt = document.createElement('option');
      opt.value = emp.EmpID;
      opt.textContent = emp.EmpName;
      select.appendChild(opt);
    });
  }

  wrapper.appendChild(select);
  return wrapper;
}

function addEmployeeDropdown() {
  const dept = departmentSelect.value;
  if (!dept) {
    alert("Please select a department first.");
    return;
  }
  const dropdown = createEmployeeDropdown(dept);
  employeeContainer.appendChild(dropdown);
}

departmentSelect.addEventListener('change', () => {
  employeeContainer.innerHTML = '';
  addEmployeeDropdown();
});
</script>

</body>
</html>
