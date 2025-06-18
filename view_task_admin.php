<?php
session_start();
include('config.php');

// Get admin name from session
$adminName = "Admin";
if (isset($_SESSION['id'])) {
    $adminID = $_SESSION['id'];
    $result = mysqli_query($db, "SELECT AdminName FROM admin WHERE AdminID = '$adminID'");
    if ($row = mysqli_fetch_assoc($result)) {
        $adminName = $row['AdminName'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Task List</title>
  <!--<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />-->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
  


  
  <style>
    * {
      color: black !important;
    }

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

    .avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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


    .alert {
      font-weight: bold;
    }

    .btn-primary {
  background-color: #4caf50;
  color: white !important;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: bold;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  transition: background-color 0.3s ease;
  text-decoration: none; /* <-- Removes underline */
  display: inline-block;
}
a.btn-primary {
  text-decoration: none;
  color: white !important;
}


.btn-primary:hover {
  background-color: #45a049; /* Darker green */
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
    <a href="employee_list.php">👥 Employee List</a>
    <a href="logoutAdmin.php">🚪 Logout</a>
  </div>

    <div class="main-content">
      <header>
        <div class="header-title">Task List</div>
        
          
           <div class="profile">👤 Admin: <?php echo($adminName); ?>
          </div>
        
      </header>

      <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          Task has been successfully deleted.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <div class="task-list">
        <table class="table table-bordered table-striped" id="dataTable">
          <thead>
            <tr>
              <th style="width: 15%;">Task Id</th>
              <th>Task Title</th>
              
                 <th style="width: 120px; ">Action</th>

            
             
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT TaskID, Title FROM task";
            $result = mysqli_query($db, $sql);

            if (mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['TaskID']}</td>
                        <td class='title-column'>{$row['Title']}</td>
                        <td><a href='task_details.php?taskID={$row['TaskID']}' class='btn btn-primary '>Details</a></td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='3'>No tasks available</td></tr>";
            }
            mysqli_close($db);
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  new DataTable("#dataTable");
</script>
</body>
</html>
