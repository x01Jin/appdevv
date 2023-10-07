<?php

include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taskId = $_POST["task_id"];

    $cancelTaskQuery = "UPDATE tasks SET status = 'canceled' WHERE id = :task_id";
    $cancelTaskStmt = $pdo->prepare($cancelTaskQuery);
    $cancelTaskStmt->bindParam(':task_id', $taskId);

    if ($cancelTaskStmt->execute()) {
        echo "Task canceled successfully.";
    } else {
        echo "An error occurred while canceling the task.";
    }
}
