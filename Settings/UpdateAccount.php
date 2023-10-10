<?php

include_once "../db.php";

session_start();

if (isset($_POST["update_account"])) {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $user_id = $_SESSION["user_id"];

    $sql = "UPDATE users SET full_name = ?, email = ? WHERE id_number = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$full_name, $email, $user_id])) {
        $_SESSION["successMessage"] = "Account information updated successfully.";
    } else {
        $_SESSION["errorMessage"] = "Failed to update account information.";
    }
}

header("Location: ../AccountPages/Admin/AdminAccSettings.php");
exit();
