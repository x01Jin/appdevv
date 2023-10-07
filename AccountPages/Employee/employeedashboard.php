<?php

include_once "../../db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "employee") {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["turn_in_task"])) {
    $taskId = $_POST["task_id"];
    $completionDate = date('Y-m-d');

    $updateTaskSql = "UPDATE tasks SET status = 'finished', completion_date = ? WHERE id = ?";
    $stmt = $pdo->prepare($updateTaskSql);

    if ($stmt->execute([$completionDate, $taskId])) {
        header("Location: employeedashboard.php");
        $successMessage = "Task turned in successfully.";
    } else {
        $errorMessage = "Error turning in task: " . $stmt->errorInfo()[2];
    }
}

$errorMessage = $successMessage = "";

$user_id = $_SESSION["user_id"];

$sqlOngoing = "SELECT id, description, status, start_date,
                deadline FROM tasks WHERE employee_id = ? AND status = 'ongoing'";
$stmtOngoing = $pdo->prepare($sqlOngoing);
$stmtOngoing->execute([$user_id]);
$ongoingTasks = $stmtOngoing->fetchAll(PDO::FETCH_ASSOC);

$sqlFinished = "SELECT id, description, status, start_date,
                deadline, completion_date FROM tasks WHERE employee_id = ? AND status = 'finished'";
$stmtFinished = $pdo->prepare($sqlFinished);
$stmtFinished->execute([$user_id]);
$finishedTasks = $stmtFinished->fetchAll(PDO::FETCH_ASSOC);

include_once "../../Settings/pfpfunc.php";

define('TD_SEPARATOR', '</td><td>');

echo "<title>(Employee)Dashboard</title>";

echo '
<div class="content">

<header>
    <h1>EMPLOYEE DASHBOARD</h1>
</header>';

echo '
<nav>
    <img src="../../profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">
    <ul>
        <li><a href="employeedashboard.php"><b>Employee Dashboard</b></a></li>
        <li><a href="employeeaccountsettings.php"><b>Account settings</b></a></li>
        <li><a href="employeedashboard.php"><b>Filler</b></a></li>
    </ul>
    <form method="POST" action="../../index.php">
        <button type="submit" name="logout">Logout</button>
    </form>
</nav>';

echo '<hr><h2>Ongoing Tasks</h2><hr>';

echo '<div style="color: green;">' . $successMessage . '</div>';
echo '<div style="color: red;">' . $errorMessage . '</div>';

echo '<table border="1">';

echo '<tr><th>Description</th><th>Status</th><th>Start Date</th><th>Deadline</th><th>Actions</th></tr>';

if (empty($ongoingTasks)) {
    echo '<tr><td colspan="4">No ongoing tasks.</td></tr>';
} else {
    foreach ($ongoingTasks as $task) {
        echo '
            <tr>
                <td>' .
                $task['description'] . TD_SEPARATOR .
                $task['status'] . TD_SEPARATOR .
                $task['start_date'] . TD_SEPARATOR .
                $task['deadline'] .
                '</td>
                <td>
                    <form method="POST" action="employeedashboard.php">
                        <input type="hidden" name="task_id" value="' . $task['id'] . '">
                        <button type="submit" name="turn_in_task">Turn In</button>
                    </form>
                </td>
            </tr>';
    }
}

echo '</table>';

echo '
<hr><h2>Finished Tasks</h2><hr>
<table border="1">
<tr><th>Description</th><th>Status</th><th>Start Date</th><th>Deadline</th><th>Completion Date</th></tr>';

if (empty($finishedTasks)) {
    echo '<tr><td colspan="5">No finished tasks.</td></tr>';
} else {
    foreach ($finishedTasks as $task) {
        echo '
            <tr>
                <td>' .
                $task['description'] . TD_SEPARATOR .
                $task['status'] . TD_SEPARATOR .
                $task['start_date'] . TD_SEPARATOR .
                $task['deadline'] . TD_SEPARATOR .
                $task['completion_date'] .
                '</td>
            </tr>';
    }
}

echo '</table>';

echo '
<br><br>
<footer>
    &copy; ' . date("Y") . ' Task Management System By CroixTech
</footer>

</div>';

echo "
<style>
body {
    background-image: url('../../assets/employee.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    font-family: Arial, sans-serif;
    text-align: center;
    color: white;
}
.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: 20px auto;
    display: block;
}
table {
    margin: 0 auto;
    width: 80%;
    color: white;
}
table th {
    background-color: rgba(0, 0, 0, 0.5);
    padding: 10px;
}
table td {
    padding: 10px;
}
header {
    background-color: rgba(51, 51, 51, 0.5);
    color: white;
    text-align: center;
    padding: 20px;
}
.content {
    margin-left: 200px;
    padding: 20px;
    background-color: rgba(51, 51, 51, 0.5);
    color: white;
}
nav {
    background-color: rgba(20, 20, 20, 100);
    color: white;
    padding: 10px;
    float: left;
    width: 200px;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
}
nav ul {
    list-style: none;
    padding: 0;
}
nav ul li {
    margin-bottom: 10px;
}
nav ul li a {
    text-decoration: none;
    color: white;
    display: block;
    padding: 5px;
}
footer {
    background-color: rgba(51, 51, 51, 0.8);
    color: white;
    text-align: center;
    padding: 10px;
}
</style>
";

echo '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../../script.js"></script>';
