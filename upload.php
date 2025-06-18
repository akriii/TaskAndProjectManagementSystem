<?php
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
    header("Location: index.html");
    exit();
}


$employeeId = $_SESSION['id'];
$taskId = $_GET['task_id'];

$employeeName = "Employee";
$nameSql = "SELECT EmpName FROM employee WHERE EmpID = '$employeeId'";
$EmpName = mysqli_query($db,$nameSql);
if($row = mysqli_fetch_assoc($EmpName)){
  $employeeName = $row['EmpName'];
}


$sql = "SELECT t.TaskID, t.Title, t.Desc, t.DueDate, t.Attachment, et.Comment, et.Status
        FROM task t
        JOIN employee_task et ON t.TaskID = et.TaskID
        WHERE et.EmpID = '$employeeId' AND t.TaskID = '$taskId'
        LIMIT 1";

$result = mysqli_query($db, $sql);
$tasks = [];

if ($result && mysqli_num_rows($result) > 0) {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upload Work</title>
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

    .dropdown-content {
      display: none;
      flex-direction: column;
      background-color: #f9f9f9;
      margin-left: 10px;
    }

    .dropdown-content a {
      padding-left: 30px;
      border-bottom: 1px solid #ddd;
      background-color: #f9f9f9;
    }

    .main {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
    }

    .header {
      font-size: 32px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
    }

    .upload-container {
      background-color: white;
      border-radius: 8px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: 0 auto;
    }

    .task-info {
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }

    .task-info h3 {
      margin-top: 0;
      color: #333;
    }

    .upload-area {
      border: 2px dashed #ccc;
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      margin-bottom: 20px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .upload-area:hover {
      border-color: #33b5e5;
      background-color: #f9f9f9;
    }

    .upload-area.highlight {
      border-color: #4CAF50;
      background-color: #f0fff0;
    }

    .upload-icon {
      font-size: 48px;
      color: #33b5e5;
      margin-bottom: 15px;
    }

    .file-input-wrapper {
      position: relative;
      margin: 20px 0;
    }

    #fileInput {
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }

    .file-list {
      margin-top: 20px;
      border-top: 1px solid #eee;
      padding-top: 15px;
    }

    .file-item {
      display: flex;
      align-items: center;
      padding: 10px;
      background-color: #f5f5f5;
      border-radius: 4px;
      margin-bottom: 10px;
    }

    .file-icon {
      margin-right: 10px;
      color: #33b5e5;
    }

    .file-name {
      flex-grow: 1;
    }

    .file-size {
      color: #777;
      margin-right: 15px;
    }

    .remove-file {
      color: #f44336;
      cursor: pointer;
      font-weight: bold;
    }

    .action-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 30px;
    }

    button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      font-size: 16px;
    }

    .submit-btn {
      background-color: #4CAF50;
      color: white;
    }

    .submit-btn:hover {
      background-color: #3e8e41;
    }

    .cancel-btn {
      background-color: #f44336;
      color: white;
    }

    .cancel-btn:hover {
      background-color: #d32f2f;
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

    .profile img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-bottom: 5px;
    }

    .dropdown-arrow {
      float: right;
      font-size: 14px;
    }

    .progress-bar {
      height: 5px;
      background-color: #e0e0e0;
      border-radius: 3px;
      margin-top: 10px;
      overflow: hidden;
    }

    .progress {
      height: 100%;
      background-color: #4CAF50;
      width: 0%;
      transition: width 0.3s;
    }

  </style>
</head>
<body>

<div class="container">
        <!-- Sidebar (unchanged) -->
        <div class="sidebar">
      <a href="employee_homepage.php" class="active">🏠 Homepage</a>
      <a href="employee_profile.php">🙍‍♂️ Profile</a>
      <a href="task_history.php" >📜 Task History</a>
      <a href="logout.php">🚪 Logout</a>
    </div>

     <div class="profile">👋 Employee: <?php echo $employeeName; ?></div>


        <div class="main">
            

            <div class="header">Upload Your Work</div>

            <?php if (!empty($tasks)) : ?>
                <div class="upload-container">
                    <?php foreach ($tasks as $task) : ?>
                        <div class="task-info">
                            <h3>Task: <?= ($task['Title']) ?></h3>
                            <p><strong>Due Date:</strong> <?= ($task['DueDate']) ?></p>
                            <p><strong>Status:</strong> <?= ($task['Status']) ?></p>
                        </div>
                            <form class="uploadForm" data-task-id="<?= $task['TaskID'] ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="task_id" value="<?= $task['TaskID'] ?>">
                            
                            <div class="upload-area uploadArea" id="uploadArea">
                                <div class="upload-icon">📁</div>
                                <h3>Drag & Drop Files Here</h3>
                                <p>or</p>
                                <div class="file-input-wrapper">
                                    <input type="file" name="files[]" class="fileInput" multiple>
                                </div>
                                <p>Maximum file size: 10MB</p>
                            </div>

                            <div class="file-list fileList" id="fileList"></div>

                            <div class="action-buttons">
                                <button type="button" class="cancel-btn" onclick="window.location.href='task_view.php'">Cancel</button>
                                <button type="submit" class="submit-btn submitBtn" id="submitBtn"  >Submit Work</button>

                        </form>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="no-tasks">No tasks assigned to you.</p>
            <?php endif; ?>
        </div>
    </div>


<script>

  
document.querySelectorAll(".uploadForm").forEach((form, index) => {
  let selectedFiles = [];

  const uploadArea = form.querySelector(".uploadArea");
  const fileInput = form.querySelector(".fileInput");
  const fileList = form.querySelector(".fileList");
  const submitBtn = form.querySelector(".submitBtn");

  ['dragenter', 'dragover'].forEach(event => uploadArea.addEventListener(event, e => {
    e.preventDefault();
    uploadArea.classList.add('highlight');
  }));

  ['dragleave', 'drop'].forEach(event => uploadArea.addEventListener(event, e => {
    e.preventDefault();
    uploadArea.classList.remove('highlight');
  }));

  uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    handleFiles(e.dataTransfer.files);
  });

  fileInput.addEventListener("change", (e) => {
    handleFiles(e.target.files);
  });

  function handleFiles(files) {
    if (selectedFiles.length === 0) {
      fileList.innerHTML = '<h4>Selected Files:</h4>';
    }

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (file.size > 10 * 1024 * 1024) {
        alert(`File "${file.name}" exceeds 10MB.`);
        continue;
      }

      if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
        continue;
      }

      selectedFiles.push(file);

      const fileItem = document.createElement('div');
      fileItem.className = 'file-item';
      fileItem.innerHTML = `
        <span class="file-icon">📄</span>
        <span class="file-name">${file.name}</span>
        <span class="file-size">${formatFileSize(file.size)}</span>
        <span class="remove-file">✕</span>
      `;

      fileItem.querySelector(".remove-file").addEventListener("click", () => {
        selectedFiles = selectedFiles.filter(f => !(f.name === file.name && f.size === file.size));
        fileItem.remove();
        if (selectedFiles.length === 0) fileList.innerHTML = '';
      });

      fileList.appendChild(fileItem);
    }

    submitBtn.disabled = selectedFiles.length === 0;
  }

  function formatFileSize(bytes) {
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i];
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (selectedFiles.length === 0) {
      alert("No files selected.");
      return;
    }

    const formData = new FormData();
    formData.append("task_id", form.dataset.taskId);

    selectedFiles.forEach(file => {
      formData.append("files[]", file);
    });

    fetch("upload_file.php", {
      method: "POST",
      body: formData,
    })
    .then(response => response.text())
   .then(result => {
  alert("Files uploaded successfully!");
  window.location.href = `employee_homepage.php?task_id=${form.dataset.taskId}`;
})

    .catch(error => {
      console.error("Error uploading:", error);
      alert("Something went wrong.");
    });
  });
});
</script>


</body>
</html>