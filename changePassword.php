<?php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input safely
    $email = trim($_POST['email']);
    $userType = $_POST['userType'];
    $enteredCode = $_POST['code'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

    // Verify session data
    if (
        !isset($_SESSION['reset_code'], $_SESSION['reset_email'], $_SESSION['user_type']) ||
        $_SESSION['reset_code'] != $enteredCode ||
        $_SESSION['reset_email'] !== $email ||
        $_SESSION['user_type'] !== $userType
    ) {
        die("❌ Invalid or expired verification code.");
    }

    // Choose the correct table and column
    if ($userType === 'admin') {
        $stmt = $db->prepare("UPDATE admin SET AdminPassword = ? WHERE AdminEmail = ?");
    } else {
        $stmt = $db->prepare("UPDATE employee SET EmpPassword = ? WHERE EmpEmail = ?");
    }

    // Bind parameters and execute
    $stmt->bind_param("ss", $newPassword, $email);

    if ($stmt->execute()) {
        // Clear session reset data
        unset($_SESSION['reset_code'], $_SESSION['reset_email'], $_SESSION['user_type']);

        // Redirect to login page
        header("Location: index.html?message=reset_success");
        exit();
    } else {
        echo "❌ Failed to update password. Please try again.";
    }
}
?>
