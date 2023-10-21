<?php

include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $StudentID = $_POST["student_id"];

    $deleteTasksQuery = "DELETE FROM tasks WHERE student_id = :student_id";
    $deleteTasksStmt = $pdo->prepare($deleteTasksQuery);
    $deleteTasksStmt->bindParam(':student_id', $StudentID);
    $deleteTasksStmt->execute();

    $getProfilePictureQuery = "SELECT profile_picture FROM users WHERE id_number = :student_id";
    $getProfilePictureStmt = $pdo->prepare($getProfilePictureQuery);
    $getProfilePictureStmt->bindParam(':student_id', $StudentID);
    $getProfilePictureStmt->execute();
    $profilePicture = $getProfilePictureStmt->fetchColumn();

    if (!empty($profilePicture)) {
        $profilePicturePath = "../../profile_pictures/" . $profilePicture;
        if (file_exists($profilePicturePath)) {
            if (unlink($profilePicturePath)) {
                echo "Success: ";
            } else {
                echo "Error: ";
            }
        }
    }

    $deleteStudentQuery = "DELETE FROM users WHERE id_number = :student_id";
    $deleteStudentStmt = $pdo->prepare($deleteStudentQuery);
    $deleteStudentStmt->bindParam(':student_id', $StudentID);

    if ($deleteStudentStmt->execute()) {
        echo "Student Removed, the tasks and the associated data of the student have been deleted.";
    } else {
        echo "An error occurred while removing the student and its data.";
    }
}
