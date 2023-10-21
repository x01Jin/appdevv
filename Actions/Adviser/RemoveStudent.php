<?php

include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $StudentID = $_POST["student_id"];

    $deleteTasksQuery = "DELETE FROM tasks WHERE student_id = :student_id";
    $deleteTasksStmt = $pdo->prepare($deleteTasksQuery);
    $deleteTasksStmt->bindParam(':student_id', $StudentID);
    $deleteTasksStmt->execute();

    $deleteStudentQuery = "DELETE FROM users WHERE id_number = :student_id";
    $deleteStudentStmt = $pdo->prepare($deleteStudentQuery);
    $deleteStudentStmt->bindParam(':student_id', $StudentID);

    if ($deleteStudentStmt->execute()) {
        echo "Student removed and the Tasks of the student have been deleted";
    } else {
        echo "An error occurred while removing the student.";
    }
}
