<?php
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $employeeId = $_SESSION['id'];

    // Raw input for email (no validation)
    $email = $_POST['email'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $department = trim($_POST['department']);

    // Basic check to avoid saving empty required fields
    if (empty($email) || empty($username) || empty($password) || empty($department)) {
        die("All fields are required.");
    }

    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Use prepared statement for security
    $stmt = $db->prepare("UPDATE employee SET EmpEmail = ?, EmpName = ?, EmpPassword = ?, Department = ? WHERE EmpID = ?");
    $stmt->bind_param("ssssi", $email, $username, $hashedPassword, $department, $employeeId);

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: employee_profile.php');
        exit();
    } else {
        $stmt->close();
        die("Failed to update.");
    }
}
?>
