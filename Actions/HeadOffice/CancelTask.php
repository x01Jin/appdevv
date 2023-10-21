<?php

include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taskId = $_POST["task_id"];

    $deleteTaskQuery = "DELETE FROM tasks WHERE id = :task_id";
    $deleteTaskStmt = $pdo->prepare($deleteTaskQuery);
    $deleteTaskStmt->bindParam(':task_id', $taskId);

    if ($deleteTaskStmt->execute()) {
        echo "Task deleted successfully.";
    } else {
        echo "An error occurred while deleting the task.";
    }
}
