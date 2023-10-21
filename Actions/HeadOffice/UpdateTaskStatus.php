<?php
include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["taskId"]) && isset($_POST["action"])) {
    $taskId = $_POST["taskId"];
    $action = $_POST["action"];

    $newStatus = ($action === "Ongoing") ? "ongoing" : "finished";

    $updateSql = "UPDATE tasks SET status = :status WHERE id = :taskId";
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute(array(':status' => $newStatus, ':taskId' => $taskId));

    echo "Task status updated successfully.";
} else {
    echo "Invalid request or missing parameters.";
}
