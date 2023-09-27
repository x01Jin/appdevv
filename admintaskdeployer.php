<?php

include_once "db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: appdev.php");
    exit();
}

$errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deploy_task"])) {
    $taskDescription = $_POST["task_description"];
    $employeeId = $_POST["employee_id"];
    $startDate = $_POST["start_date"];
    $deadline = $_POST["deadline"];

    $insertTaskSql = "INSERT INTO tasks (description, employee_name, start_date, deadline) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertTaskSql);

    $employeeNameSql = "SELECT full_name FROM users WHERE id = ?";
    $employeeNameStmt = $pdo->prepare($employeeNameSql);
    $employeeNameStmt->execute([$employeeId]);
    $employeeName = $employeeNameStmt->fetchColumn();

    if ($stmt->execute([$taskDescription, $employeeName, $startDate, $deadline])) {
        $successMessage = "Task deployed successfully.";
        header("Location: admintaskdeployer.php");
    } else {
        $errorMessage = "Error deploying task: " . $stmt->errorInfo()[2];
    }
}

$sql = "SELECT id, full_name, id_number FROM users WHERE role = 'employee'";
$employees = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo "<title>(Admin)Task Deployer</title>";

echo '

<div class="content">

<header>

<h1>DEPLOY TASK</h1>
<form method="POST" action="appdev.php">
<button type="submit" name="logout">Logout</button>
</form>

</header>';

echo '
<nav>

<ul>

<li><a href="admindashboard.php" style="color:white;"><b>Admin Dashboard</b></a></li>
<li><a href="admintaskdeployer.php" style="color:white;"><b>Deploy Task</b></a></li>
<li><a href="adminaddemployee.php" style="color:white;"><b>Add Employee</b></a></li>

</ul>

</nav>';

echo '

<form method="POST" action="admintaskdeployer.php">

<label for="task_description"><h2>Task Description</h2></label>
<input type="text" id="task_description" name="task_description" required><hr>
<label for="employee_id"><h2>Assign to Employee</h2></label>
<select id="employee_id" name="employee_id" required>';

foreach ($employees as $employee) {
    echo '<option value="' . $employee['id'] . '">' . $employee['full_name'] . '</option>';
}

echo '
</select><hr>
<label for="start_date"><h2>Start Date</h2></label>
<input type="date" id="start_date" name="start_date" required><br><hr>
<label for="deadline"><h2>Deadline</h2></label>
<input type="date" id="deadline" name="deadline" required><hr><br>
<button type="submit" name="deploy_task">Deploy Task</button>
</form><br><br>';

echo '
<footer>
    &copy; <?php echo date("Y"); ?> Task Management System By CroixTech
</footer>
</div>';

echo "
<style>

body {
    background-image: url('assets/admin.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    font-family: Arial, sans-serif;
    text-align: center;
    color: white;
}

table {
    margin: 0 auto;
}

table th {
    color: white;
}

table td {
    color: white;
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
    display: ;
    margin-bottom: 10px;
}

nav ul li a {
    text-decoration: none;
    color: #333;
    display: block;
    padding: 5px;
}

main {
    padding: 20px;
}

footer {
    background-color: rgba(51, 51, 51, 0.8);
    color: white;
    text-align: center;
    padding: 10px;
}

.task-preview {
    display: none;
    position: absolute;
    background-color: rgba(249, 249, 249, 0.8);
    border: 1px solid #ccc;
    padding: 10px;
    z-index: 1;
    color: black;
}

.calendar-popup {
    display: none;
    position: absolute;
    background-color: rgba(255, 255, 255, 0.8);
    border: 1px solid #ccc;
    padding: 10px;
    z-index: 1;
}
</style>
";

echo '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="script.js"></script>';
