<?php
include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["taskId"]) && isset($_POST["action"])) {
    $taskId = $_POST["taskId"];
    $action = $_POST["action"];

    // Check if the action is "Ongoing" or "Finalize" and update the task status accordingly
    $newStatus = ($action === "Ongoing") ? "ongoing" : "finished";

    // Update the task status in the database
    $updateSql = "UPDATE tasks SET status = :status WHERE id = :taskId";
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute(array(':status' => $newStatus, ':taskId' => $taskId));

    // You can perform additional error handling if needed

    // Return a success message or response if required
    echo "Task status updated successfully.";
} else {
    // Handle invalid requests or errors
    echo "Invalid request or missing parameters.";
}
