<?php

include_once "../db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$profilePictureDirectory = "../profile_pictures/";
if (!file_exists($profilePictureDirectory)) {
    mkdir($profilePictureDirectory, 0755, true);
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profilePictureName = $_FILES["profile_picture"]["name"];
    $profilePictureTmpName = $_FILES["profile_picture"]["tmp_name"];

    if (!empty($profilePictureName)) {
        $uniqueProfilePictureName = uniqid() . '_' . $profilePictureName;
        $destinationPath = $profilePictureDirectory . $uniqueProfilePictureName;
        if (move_uploaded_file($profilePictureTmpName, $destinationPath)) {
            $sql = "SELECT profile_picture FROM users WHERE id_number = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id]);
            $currentProfilePicture = $stmt->fetchColumn();

            $sql = "UPDATE users SET profile_picture = ? WHERE id_number = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$uniqueProfilePictureName, $user_id]);

            if ($currentProfilePicture !== "default.jpg") {
                $oldProfilePicturePath = $profilePictureDirectory . $currentProfilePicture;
                if (file_exists($oldProfilePicturePath)) {
                    unlink($oldProfilePicturePath);
                }
            }

            $successMessage = "Profile picture updated successfully.";
        } else {
            $errors[] = "Failed to upload the profile picture.";
        }
    } else {
        $errors[] = "No profile picture selected.";
    }
}

header("Location: ../AccountPages/Admin/AdminAccSettings.php");

exit();
