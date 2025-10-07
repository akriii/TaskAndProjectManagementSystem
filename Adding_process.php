<?php
session_start(); // Required to access session variables
include("config.php");

// Check if admin is logged in
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Admin not logged in.'); window.location.href='indexLogin.php';</script>";
    exit;
}

$adminID = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $Title = mysqli_real_escape_string($db, $_POST['title']);
    $Desc = mysqli_real_escape_string($db, $_POST['desc']);
    $Due_date = $_POST['due_date'];
    $Status = $_POST['status'];
    $EmpIDs = $_POST['employee_id'];
    $attachmentName = "";

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'][0] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $originalName = basename($_FILES["attachment"]["name"][0]);
        $targetFile = $targetDir . time() . "_" . $originalName;

        if (move_uploaded_file($_FILES["attachment"]["tmp_name"][0], $targetFile)) {
            $attachmentName = $targetFile;
        } else {
            echo "<script>alert('File upload failed.'); window.location.href='add_task.php';</script>";
            exit;
        }
    }

    // Insert into task with AdminID
    $sql = "INSERT INTO task (Title, `Desc`, DueDate, Attachment, AdminID) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssssi", $Title, $Desc, $Due_date, $attachmentName, $adminID);

    if ($stmt->execute()) {
        $taskID = $stmt->insert_id;

        $stmt2 = $db->prepare("INSERT INTO employee_task (EmpID, TaskID, Status) VALUES (?, ?, ?)");
        foreach ($EmpIDs as $EmpID) {
            $stmt2->bind_param("iis", $EmpID, $taskID, $Status);
            $stmt2->execute();
        }

        header("Location: view_task_admin.php");
        exit();
    } else {
        echo "<script>alert('Failed to create task.'); window.location.href='add_task.php';</script>";
    }
}
?>
