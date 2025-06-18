<?php
session_start();
include('config.php');

if (!isset($_GET['taskID']) || !isset($_GET['empID'])) {
    die("Missing taskID or empID.");
}

$taskID = $_GET['taskID'];
$empID = $_GET['empID'];

// Remove employee from the task
$sql = "DELETE FROM employee_task WHERE TaskID = ? AND EmpID = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("ii", $taskID, $empID);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Redirect back to employee list
    header("Location: task_details.php?taskID=$taskID");
    exit();
} else {
    echo "Failed to remove employee from task.";
}
?>
