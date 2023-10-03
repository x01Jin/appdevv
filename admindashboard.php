<?php

include_once "db.php";

define('TD_SEPARATOR', '</td><td>');

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: appdev.php");
    exit();
}

$errorMessage = $successMessage = "";

$sql = "SELECT id_number, full_name FROM users WHERE role = 'employee'";
$employees = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT id, description, employee_name, start_date, deadline FROM tasks";
$tasks = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT profile_picture FROM users WHERE id_number = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$profilePicture = $user['profile_picture'];

echo "<title>(Admin)Dashboard</title>";

echo '
<div class="content">

<header>
<h1>ADMIN DASHBOARD</h1>
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
<hr><h2>Employee List</h2><hr>
<table border="1">
<caption>List of employees</caption>
<tr>
<th>Full Name</th>
<th>ID Number</th>
</tr>';

foreach ($employees as $employee) {
    echo '
    <tr>
    <td>' . $employee['full_name'] . '</td>
    <td>' . ($employee['id_number'] ? $employee['id_number'] : 'N/A') . '</td>
    </tr>';
}

echo '
</table>';

echo '<section id="task-list">
<hr></hr><h2>Task List</h2><hr></hr>
<table border="1">
<caption>List of tasks</caption>
<tr>
<th>Task ID</th>
<th>Description</th>
<th>Employee Name</th>
<th>Start Date</th>
<th>Deadline</th>
</tr>';

foreach ($tasks as $task) {
    echo '
    <tr class="task-row" data-start-date="' . $task['start_date'] . '" data-deadline="' . $task['deadline'] . '">
    <td>' .
    $task['id'] . TD_SEPARATOR .
    $task['description'] . TD_SEPARATOR .
    $task['employee_name'] . TD_SEPARATOR .
    $task['start_date'] . TD_SEPARATOR .
    $task['deadline'] .
    '</td>
    </tr>';
}

echo '
</table>
</section>
<br><br>';

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
<div class="task-preview"></div>';

echo '
<div class="calendar-popup"></div>';

echo '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="script.js"></script>';
