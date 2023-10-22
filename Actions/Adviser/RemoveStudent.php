<?php

include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_student"])) {
    $studentId = $_POST["delete_student"];

    function deleteTasks($studentId) {
        global $pdo;
        $sql = "DELETE FROM tasks WHERE student_id = :studentId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
    }

    function deleteStudent($studentId) {
        global $pdo;
        $sql = "DELETE FROM users WHERE id_number = :studentId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
    }

    $sqlCheckTasks = "SELECT COUNT(*) FROM tasks WHERE student_id = :studentId";
    $stmtCheckTasks = $pdo->prepare($sqlCheckTasks);
    $stmtCheckTasks->bindParam(':studentId', $studentId);
    $stmtCheckTasks->execute();
    $taskCount = $stmtCheckTasks->fetchColumn();

    if ($taskCount > 0) {
        deleteTasks($studentId);
    }

    deleteStudent($studentId);

    $response = array(
        'success' => true,
        'message' => 'Student deleted successfully.'
    );
    echo json_encode($response);
    exit();
}

$response = array(
    'success' => false,
    'message' => 'Invalid request.'
);
echo json_encode($response);
exit();
