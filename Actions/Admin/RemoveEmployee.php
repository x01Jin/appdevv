<?php

include_once "../../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employeeId = $_POST["employee_id"];

    $deleteTasksQuery = "DELETE FROM tasks WHERE employee_id = :employee_id";
    $deleteTasksStmt = $pdo->prepare($deleteTasksQuery);
    $deleteTasksStmt->bindParam(':employee_id', $employeeId);
    $deleteTasksStmt->execute();

    $deleteEmployeeQuery = "DELETE FROM users WHERE id_number = :employee_id";
    $deleteEmployeeStmt = $pdo->prepare($deleteEmployeeQuery);
    $deleteEmployeeStmt->bindParam(':employee_id', $employeeId);

    if ($deleteEmployeeStmt->execute()) {
        echo "Employee removed and the Tasks of the employee have been deleted";
    } else {
        echo "An error occurred while removing the employee.";
    }
}
