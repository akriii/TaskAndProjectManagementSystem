<?php
session_start();
include("config.php");

// Semak jika admin sudah login dan usertype betul
if (!isset($_SESSION['id']) || $_SESSION['usertype'] !== 'admin') {
    die("Access denied. Admin not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['send'])) {
    // Dapatkan input dan bersihkan
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $adminId = $_SESSION['id'];

   
    $sql = "UPDATE admin SET AdminEmail = '$email', AdminName = '$username', AdminPassword = '$password' WHERE AdminID = '$adminId'";
    $query = mysqli_query($db, $sql);

    if ($query) {
        echo "<script>alert('Profile updated successfully.'); window.location.href='admin_profile.php';</script>";
    } else {
        echo "Update failed: " . mysqli_error($db);
    }
} else {
    die("Invalid request.");
}
?>
