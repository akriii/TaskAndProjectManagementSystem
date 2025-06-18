<?php
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
    die("Admin not logged in.");
}

$adminID = intval($_SESSION['id']);
$taskId = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;

if (!$taskId || empty($_FILES['files'])) {
    die("Missing task ID or file.");
}

$uploadDir = "uploads/attachment/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
$maxSize = 10 * 1024 * 1024;
$fileProcessed = false;

foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
    if ($_FILES['files']['error'][$key] !== 0) continue;

    $fileName = basename($_FILES['files']['name'][$key]);
    $fileType = $_FILES['files']['type'][$key];
    $fileSize = $_FILES['files']['size'][$key];
    $uniqueName = uniqid() . '_' . $fileName;
    $targetFile = $uploadDir . $uniqueName;

    if (!in_array($fileType, $allowedTypes) || $fileSize > $maxSize) continue;
    if (!move_uploaded_file($tmpName, $targetFile)) continue;

    $stmt1 = $db->prepare("UPDATE task SET Attachment = ? WHERE TaskID = ?");
    $stmt1->bind_param("si", $uniqueName, $taskId);
    $stmt1->execute();
    $stmt1->close();

    $stmt2 = $db->prepare("INSERT INTO admin_task (AdminID, TaskID, StartDate) VALUES (?, ?, NOW())");
    $stmt2->bind_param("ii", $adminID, $taskId);
    $stmt2->execute();
    $stmt2->close();

    $fileProcessed = true;
}

$message = $fileProcessed ? 'File uploaded and task updated successfully.' : 'No valid file was processed.';
echo "<script>alert('$message'); window.location.href='add_task.php';</script>";
?>
