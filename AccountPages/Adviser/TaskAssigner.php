<?php

include_once "../../db.php";

define('TD_SEPARATOR', '</td><td');

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "adviser") {
    header("Location: ../../index.php");
    exit();
}

$errorMessage = $successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign-tasks'])) {
    if (isset($_POST['task'])) {
        $assignedTasks = $_POST['task'];

        foreach ($assignedTasks as $taskId) {
            $studentId = $_POST['student'][$taskId];
            $startDate = $_POST['start_date'][$taskId];
            $deadline = $_POST['deadline'][$taskId];

            $sql = "UPDATE tasks SET student_id = :studentId, status = 'ongoing',
                    start_date = :startDate, deadline = :deadline
                    WHERE id = :taskId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':studentId' => $studentId,
                            ':startDate' => $startDate,
                            ':deadline' => $deadline,
                            ':taskId' => $taskId]);

            $sqlStudent = "SELECT full_name FROM users WHERE id_number = :studentId";
            $stmtStudent = $pdo->prepare($sqlStudent);
            $stmtStudent->execute([':studentId' => $studentId]);
            $studentName = $stmtStudent->fetchColumn();

            $sqlUpdateName = "UPDATE tasks SET student_name = :studentName WHERE id = :taskId";
            $stmtUpdateName = $pdo->prepare($sqlUpdateName);
            $stmtUpdateName->execute([':studentName' => $studentName, ':taskId' => $taskId]);
        }

        $successMessage = 'Tasks have been successfully assigned.';
    } else {
        $errorMessage = 'No tasks were selected for assignment.';
    }
}

$sql = "SELECT id, description FROM tasks WHERE status = 'requested'";
$requestedTasks = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sqlStudents = "SELECT id_number, full_name FROM users WHERE role = 'student'";
$students = $pdo->query($sqlStudents)->fetchAll(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";

?>

<title>(Adviser) Task Assigner</title>

<div class="content">
    <header>
    <h1>TASK ASSIGNER</h1>
    </header>

    <nav>
        <?php
        echo '<img src="../../profile_pictures/' . $profilePicture  . '" alt="Profile Picture" class="profile-picture">'
        ?>
        <ul>
            <li><a href="AdviserDashboard.php" style="color:white;"><b>Adviser Dashboard</b></a></li>
            <li><a href="TaskAssigner.php" style="color:white;"><b>Assign Task</b></a></li>
            <li><a href="AddStudent.php" style="color:white;"><b>Add Student</b></a></li>
            <li><a href="AdviserAccSettings.php" style="color:white;"><b>Account Settings</b></a></li>
        </ul>
        <form method="POST" action="../../index.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    </nav>

    <section id="assign-tasks">
        <hr><h2>Assign Tasks</h2><hr>
        <form method="POST" name="assign-tasks-form">
            <table border="1">
                <caption>List of Requested Tasks</caption>
                <tr>
                    <th>Select</th>
                    <th>Task Description</th>
                    <th>Student</th>
                    <th>Start Date</th>
                    <th>Deadline</th>
                </tr>

                <?php
                foreach ($requestedTasks as $task) {
                    echo '<tr>';
                    echo '<td><input type="checkbox" name="task[]" value="' . $task['id'] . '"></td>';
                    echo '<td>' . $task['description'] . '</td>';
                    echo '<td>
                            <select name="student[' . $task['id'] . ']">
                                <option value="">Unassigned</option>';
                    foreach ($students as $student) {
                        echo '<option value="' . $student['id_number'] . '">' . $student['full_name'] . '</option>';
                    }
                    echo '</select>
                          </td>';
                          echo '<td><input type="date" name="start_date[' . $task['id'] . ']"
                                pattern="\d{4}-\d{2}-\d{2}"></td>';
                          echo '<td><input type="date" name="deadline[' . $task['id'] . ']"
                                pattern="\d{4}-\d{2}-\d{2}"></td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <button type="submit" name="assign-tasks">Assign Tasks</button>
        </form>
    </section>

    <?php
    if (!empty($errorMessage)) {
        echo '<div class="error-message">' . $errorMessage . '</div>';
    }
    if (!empty($successMessage)) {
        echo '<div class="success-message">' . $successMessage . '</div>';
    }
    ?>

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
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const assignTasksForm = document.querySelector('form[name="assign-tasks-form"]');

        if (assignTasksForm) {
            assignTasksForm.addEventListener("submit", function (event) {
                if (!confirm("Are you sure you want to assign these tasks?")) {
                    event.preventDefault();
                }
            });
        }
    });
</script>
