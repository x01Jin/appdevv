<?php

include_once "../../db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "student") {
    header("Location: ../../index.php");
    exit();
}

$errorMessage = $successMessage = "";

$user_id = $_SESSION["user_id"];

$sqlOngoing = "SELECT id, description, status, start_date,
                deadline FROM tasks WHERE student_id = ? AND status = 'ongoing'";
$stmtOngoing = $pdo->prepare($sqlOngoing);
$stmtOngoing->execute([$user_id]);
$ongoingTasks = $stmtOngoing->fetchAll(PDO::FETCH_ASSOC);

$sqlFinished = "SELECT id, description, status, start_date,
                deadline, completion_date FROM tasks WHERE student_id = ? AND status = 'finished'";
$stmtFinished = $pdo->prepare($sqlFinished);
$stmtFinished->execute([$user_id]);
$finishedTasks = $stmtFinished->fetchAll(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";

define('TD_SEPARATOR', '</td><td>');

?>

<title>(Student)Dashboard</title>

<div class="content">
    <header>
        <h1>STUDENT DASHBOARD</h1>
    </header>

    <nav>
        <?php echo '
        <img src="../../profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">'
        ?>
        <ul>
            <li><a href="StudentDashboard.php"><b>Student Dashboard</b></a></li>
            <li><a href="StudentAccSettings.php"><b>Account settings</b></a></li>
        </ul>
        <form class="logout-form" method="POST" action="../../index.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    </nav>

    <hr><h2>Ongoing Tasks</h2><hr>
    <table border="1">
        <caption><br><b>CONSULT REGISTRAR FOR TASK SUBMISSION</b><br><br></caption>
        <tr>
            <th>Description</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Deadline</th>
        </tr>

        <?php
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
                    </tr>';
            }
        }
        ?>
    </table>

    <hr><h2>Finished Tasks</h2><hr>

    <table border="1">
        <caption></caption>
        <tr>
            <th>Description</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Deadline</th>
            <th>Completion Date</th>
        </tr>
        <?php
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
        ?>
    </table>

    <br><br>

    <footer>
        &copy; <?php echo date("Y"); ?> Task Management System By CroixTech
    </footer>
</div>

<style>
body {
    background-image: url('../../assets/taskitbg.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
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

footer {
    background-color: rgba(51, 51, 51, 0.8);
    color: white;
    text-align: center;
    padding: 10px;
}
</style>

<link href="../../API/Nav.css" rel="stylesheet">
