<?php

include_once "db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "employee") {
    header("Location: appdev.php");
    exit();
}

$errorMessage = $successMessage = "";

$sql = "SELECT id, description, start_date, deadline FROM tasks WHERE employee_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once "pfpfunc.php" ;

echo "<title>(Employee)Dashboard</title>";

echo '
<div class="content">
<header>
<h1>EMPLOYEE DASHBOARD</h1>
<form method="POST" action="appdev.php">
<button type="submit" name="logout">Logout</button>
</form>
</header>';

echo '
<nav>
<img src="profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">
<ul>
<li><a href="employeedashboard.php"><b>Employee Dashboard</b></a></li>
<li><a href="filler.php"><b>filler</b></a></li>
<li><a href="filler.php"><b>filler</b></a></li>
</ul>
</nav>';

echo '<hr><h2>Your Tasks</h2><hr>';

echo '<table border="1">';

echo '<tr><th>Description</th><th>Start Date</th><th>Deadline</th></tr>';

if (empty($tasks)) {
    echo '<tr><td colspan="3">No tasks assigned yet.</td></tr>';
} else {
    foreach ($tasks as $task) {
        echo '
            <tr>
                <td>' . $task['description'] . '</td>
                <td>' . $task['start_date'] . '</td>
                <td>' . $task['deadline'] . '</td>
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
    background-image: url('assets/employee.jpg');
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
<script src="script.js"></script>';
