<?php
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

$employeeId = $_SESSION['id'];

if (!isset($_POST['task_id']) || empty($_FILES['files']['name'][0])) {
    die("Missing task ID or files.");
}

$taskId = $_POST['task_id'];
$comment = $_POST['comment'];
$uploadDir = "uploads/uploadedTask/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir);
}

$fileProcessed = false;

foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
    $name = $_FILES['files']['name'][$key];
    $uniqueName = uniqid() . "_" . $name; // unique name for file is used to prevent same file name been replace
    $target = $uploadDir . $uniqueName;

    if (move_uploaded_file($tmpName, $target)) {
        $sql = "INSERT INTO employee_task (EmpID, TaskID, UploadedTask, Comment, Status)
                VALUES ('$employeeId', '$taskId', '$target', '$comment', 'Submitted')
                ON DUPLICATE KEY UPDATE 
                    UploadedTask = '$target',
                    Comment = '$comment',
                    Status = 'Completed'";

        if (mysqli_query($db, $sql)) {
            $fileProcessed = true;
        } else {
            unlink($target);
        }
    }
}

if ($fileProcessed) {
    echo "<script>alert('Files uploaded successfully!'); window.location.href='task_view.php';</script>";
} else {
    echo "<script>alert('No files uploaded.'); window.location.href='task_view.php';</script>";
}
?>
