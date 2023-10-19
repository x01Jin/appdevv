<?php

include_once "../../db.php";

define('TD_SEPARATOR', '</td><td>');

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "adviser") {
    header("Location: ../../index.php");
    exit();
}

$errorMessage = $successMessage = "";

$sql = "SELECT id_number, full_name FROM users WHERE role = 'student'";
$students = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sqlOngoing = "SELECT id, description, employee_name, start_date, deadline,
                status FROM tasks WHERE status IN ('ongoing')";
$ongoingTasks = $pdo->query($sqlOngoing)->fetchAll(PDO::FETCH_ASSOC);

$sqlFinished = "SELECT id, description, employee_name, start_date, deadline,
                status FROM tasks WHERE status = 'finished'";
$finishedTasks = $pdo->query($sqlFinished)->fetchAll(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";

?>

<title>(Admin)Dashboard</title>

<div class="content">
    <header>
    <h1>ADMIN DASHBOARD</h1>
    </header>

    <nav>
        <?php
        echo '<img src="../../profile_pictures/' . $profilePicture  . '" alt="Profile Picture" class="profile-picture">'
        ?>
        <ul>
            <li><a href="AdminDashboard.php" style="color:white;"><b>Admin Dashboard</b></a></li>
            <li><a href="TaskDeployer.php" style="color:white;"><b>Deploy Task</b></a></li>
            <li><a href="AddEmployee.php" style="color:white;"><b>Add Employee</b></a></li>
            <li><a href="AdminAccSettings.php" style="color:white;"><b>Account Settings</b></a></li>
        </ul>
        <form method="POST" action="../../index.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    </nav>

    <hr><h2>Employee List</h2><hr>
    <table border="1">
        <caption>List of employees</caption>
        <tr>
            <th>Full Name</th>
            <th>ID Number</th>
            <th>Action</th>
        </tr>

        <?php

        foreach ($students as $student) {
            echo '
                <tr>
                <td>' . $student['full_name'] . '</td>
                <td>' . ($student['id_number'] ? $student['id_number'] : 'N/A') . '</td>
                <td>
                <button class="remove-employee" data-id="' . $student['id_number'] . '">Remove</button>
                </td>
                </tr>';
        }

        ?>
    </table>

    <section id="ongoing-tasks">
        <hr><h2>Ongoing Tasks</h2><hr></hr>
        <table border="1">
            <caption>List of ongoing tasks</caption>
            <tr>
                <th>Task ID</th>
                <th>Description</th>
                <th>Employee Name</th>
                <th>Start Date</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php

            foreach ($ongoingTasks as $task) {
                echo '
                    <tr class="task-row" data-start-date="' .
                    $task['start_date'] . '" data-deadline="' .
                    $task['deadline'] . '">
                        <td>' .
                            $task['id'] . TD_SEPARATOR .
                            $task['description'] . TD_SEPARATOR .
                            $task['employee_name'] . TD_SEPARATOR .
                            $task['start_date'] . TD_SEPARATOR .
                            $task['deadline'] . TD_SEPARATOR .
                            $task['status'] .
                        '</td>
                        <td>
                            <button class="cancel-task" data-id="' . $task['id'] . '">Cancel</button>
                        </td>
                    </tr>';
            }

            ?>
        </table>
    </section>

    <section id="finished-tasks">
        <hr><h2>Finished Tasks</h2><hr></hr>
        <table border="1">
            <caption>List of finished tasks</caption>
            <tr>
                <th>Task ID</th>
                <th>Description</th>
                <th>Employee Name</th>
                <th>Start Date</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php

            foreach ($finishedTasks as $task) {
                echo '
                    <tr class="task-row" data-start-date="' .
                    $task['start_date'] . '" data-deadline="' .
                    $task['deadline'] . '">
                        <td>' .
                            $task['id'] . TD_SEPARATOR .
                            $task['description'] . TD_SEPARATOR .
                            $task['employee_name'] . TD_SEPARATOR .
                            $task['start_date'] . TD_SEPARATOR .
                            $task['deadline'] . TD_SEPARATOR .
                            $task['status'] .
                        '</td>
                        <td>
                            <button class="delete-finished-task" data-id="' . $task['id'] . '">Delete</button>
                        </td>
                    </tr>';
            }

            ?>
        </table>
    </section>
    <br><br>
    <footer>
        &copy; <?php echo date("Y"); ?> Task Management System By CroixTech
    </footer>
</div>

<style>
    body {
        background-image: url('../../assets/admin.jpg');
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

<div class="task-preview"></div>

<div class="calendar-popup"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../../script.js"></script>
<script>
    $(document).ready(function() {
        $(".remove-employee").on("click", function() {
            var employeeId = $(this).data("id");
            if (confirm("Are you sure you want to remove this employee and its tasks?")) {
                $.ajax({
                    url: '../../Actions/Admin/RemoveEmployee.php',
                    method: 'POST',
                    data: { employee_id: employeeId },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error(error);
                        alert("An error occurred while removing the employee.");
                    }
                });
            }
        });
    });

    $(document).ready(function() {
    $(".cancel-task").on("click", function() {
        var taskId = $(this).data("id");
        if (confirm("Are you sure you want to cancel this task?")) {
            $.ajax({
                url: '../../Actions/Admin/CancelTask.php',
                method: 'POST',
                data: { task_id: taskId },
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                    alert("An error occurred while canceling the task.");
                }
            });
        }
    });

    $(".delete-finished-task").on("click", function() {
        var taskId = $(this).data("id");
            if (confirm("Are you sure you want to delete this finished task?")) {
                $.ajax({
                    url: '../../Actions/Admin/DeleteFinished.php',
                    method: 'POST',
                    data: { task_id: taskId },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error(error);
                        alert("An error occurred while deleting the finished task.");
                    }
                });
            }
        });
    });
</script>
