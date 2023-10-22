<?php

include_once "../../db.php";

$response = array();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taskId = $_POST["task_id"];

    $deleteTaskQuery = "DELETE FROM tasks WHERE id = :task_id";
    $deleteTaskStmt = $pdo->prepare($deleteTaskQuery);
    $deleteTaskStmt->bindParam(':task_id', $taskId);

    if ($deleteTaskStmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Finished task deleted successfully.";
    } else {
        $response['success'] = false;
        $response['message'] = "An error occurred while deleting the finished task.";
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
