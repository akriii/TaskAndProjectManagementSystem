<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    $userType = $_POST['userType'];
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $department = ($userType === 'employee') ? trim($_POST['department']) : null;

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($userType === 'admin') {
        $adminCode = $_POST['adminCode'];

        // Validate admin code
        if ($adminCode !== 'THESECRETCODEISYOU') {
            die("Unauthorized admin registration.");
        }

        // Use prepared statement for admin
        $stmt = $db->prepare("INSERT INTO admin (AdminEmail, AdminName, AdminPassword) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $hashedPassword);

    } elseif ($userType === 'employee') {
        // Use prepared statement for employee
        $stmt = $db->prepare("INSERT INTO employee (EmpEmail, EmpName, EmpPassword, Department) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $username, $hashedPassword, $department);

    } else {
        die("Invalid user type selected.");
    }

    // Execute statement
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: addEmployee.php?status=success");
        exit();
    } else {
        $stmt->close();
        header("Location: addEmployee.php?status=fail");
        exit();
    }

} else {
    die("Access Denied!");
}
?>
