<?php
session_start();
include("config.php");

if (isset($_POST['send'])) {
    $userType = $_POST['userType'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($userType == 'admin') {
        $sql = "SELECT * FROM admin WHERE AdminEmail = '$email'";
        $redirectPage = "Adminpage.php";
        $idKey = "AdminID";
        $passKey = "AdminPassword";
    } elseif ($userType == 'employee') {
        $sql = "SELECT * FROM employee WHERE EmpEmail = '$email'";
        $redirectPage = "employee_homepage.php";
        $idKey = "EmpID";
        $passKey = "EmpPassword";
    } else {
        echo "<script>alert('Invalid user type.'); window.location.href='index.html';</script>";
        exit();
    }

    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('You are not registered.'); window.location.href='index.html';</script>";
        exit();
    } else {
        $user = mysqli_fetch_assoc($result);
        $storedPassword = $user[$passKey];

        $isPasswordCorrect = false;

        // Login check: plain for admin, hashed for employee
        if ($userType === 'admin' && $password === $storedPassword) {
            $isPasswordCorrect = true;
        } elseif ($userType === 'employee' && password_verify($password, $storedPassword)) {
            $isPasswordCorrect = true;
        }

        if ($isPasswordCorrect) {
            $_SESSION['id'] = $user[$idKey];
            $_SESSION['usertype'] = $userType;
            $userId = $_SESSION['id'];

            echo "<script>alert('Login successful'); window.location.href='" . $redirectPage . "?id=" . $userId . "';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href='index.html';</script>";
            exit();
        }
    }
}
?>
