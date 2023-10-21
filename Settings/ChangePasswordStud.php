<?php

include_once "../db.php";

session_start();

if (isset($_POST["change_password"])) {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];
    $user_id = $_SESSION["user_id"];

    if ($new_password !== $confirm_password) {
        $_SESSION["errorMessage"] = "New password and confirmation do not match.";
        header("Location: ../AccountPages/Student/StudentAccSettings.php");
        exit();
    }

    $sql = "SELECT password FROM users WHERE id_number = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($current_password, $user["password"])) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id_number = ?";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$hashed_password, $user_id])) {
            $_SESSION["successMessage"] = "Password changed successfully.";
        } else {
            $_SESSION["errorMessage"] = "Failed to change password.";
        }
    } else {
        $_SESSION["errorMessage"] = "Current password is incorrect.";
    }
}

header("Location: ../AccountPages/Student/StudentAccSettings.php");
exit();
