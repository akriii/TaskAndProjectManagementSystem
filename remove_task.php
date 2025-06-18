<?php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_SESSION['id'])) {
  $taskId = intval($_POST['task_id']);
  $employeeId = $_SESSION['id'];

  // Only allow deletion if task belongs to this employee
  $stmt = $db->prepare("DELETE FROM employee_task WHERE TaskID = ? AND EmpID = ?");
  $stmt->bind_param("ii", $taskId, $employeeId);
  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error";
  }
} else {
  echo "unauthorized";
}
