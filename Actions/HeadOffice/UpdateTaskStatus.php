<?php
include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["taskId"]) && isset($_POST["action"])) {
    $taskId = $_POST["taskId"];
    $action = $_POST["action"];

    $currentStatusSql = "SELECT status FROM tasks WHERE id = :taskId";
    $stmt = $pdo->prepare($currentStatusSql);
    $stmt->execute(array(':taskId' => $taskId));
    $currentStatus = $stmt->fetchColumn();

    $newStatus = ($action === "Ongoing") ? "ongoing" : "finished";

    $updateSql = "UPDATE tasks SET status = :status";
    
    if ($newStatus === "finished") {
        $updateSql .= ", completion_date = NOW()";
    } elseif ($newStatus === "ongoing") {
        $updateSql .= ", completion_date = NULL";
    }
    
    $updateSql .= " WHERE id = :taskId";

    $stmt = $pdo->prepare($updateSql);
    $stmt->execute(array(':status' => $newStatus, ':taskId' => $taskId));

    if ($stmt->rowCount() > 0) {
        echo "Task status updated successfully.";
    } else {
        echo "No records were updated.";
    }
} else {
    echo "Invalid request or missing parameters.";
}
