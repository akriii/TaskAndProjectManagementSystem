<?php
session_start();
include('config.php');

if (isset($_POST['TaskID'])) {
    $taskID = intval($_POST['TaskID']);

    // Start transaction
    mysqli_autocommit($db, false);

    $success1 = mysqli_query($db, "DELETE FROM employee_task WHERE TaskID = $taskID");
    $success2 = mysqli_query($db, "DELETE FROM task WHERE TaskID = $taskID");

    if ($success1 && $success2) {
        mysqli_commit($db);
        header("Location: view_task_admin.php");
        exit();
    } else {
        mysqli_rollback($db);
        echo "Error: Could not delete the task.";
    }
} else {
    echo "Error: Task ID is not provided.";
}
?>
