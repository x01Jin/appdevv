<?php

include_once "../../db.php";

define('TD_SEPARATOR', '</td><td>');

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "headoffice") {
    header("Location: ../../index.php");
    exit();
}

$errorMessage = $successMessage = "";

$sqlTasks = "SELECT id, description, student_name, start_date, deadline, status
             FROM tasks WHERE status IN ('ongoing', 'finished')";
$allTasks = $pdo->query($sqlTasks)->fetchAll(PDO::FETCH_ASSOC);

$sqlRequestedTasks = "SELECT id, description, status
                      FROM tasks
                      WHERE status = 'requested'";
$requestedTasks = $pdo->query($sqlRequestedTasks)->fetchAll(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";

?>

<title>(Head Office)Dashboard</title>

<div class="content">
    <header>
    <h1>HEAD OFFICE DASHBOARD</h1>
    </header>

    <nav>
        <?php
        echo '<img src="../../profile_pictures/' . $profilePicture  . '" alt="Profile Picture" class="profile-picture">'
        ?>
        <ul>
            <li><a href="HeadOfficeDashboard.php" style="color:white;"><b>Head Office Dashboard</b></a></li>
            <li><a href="HeadOfficeRequestTask.php" style="color:white;"><b>Request Task</b></a></li>
            <li><a href="HeadOfficeAccSettings.php" style="color:white;"><b>Account Settings</b></a></li>
        </ul>
        <form class="logout-form" method="POST" action="../../index.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    </nav>

    <section id="ongoing-tasks">
        <hr><h2>Requested Tasks</h2><hr></hr>
        <table border="1">
            <caption></caption>
            <tr>
                <th>Task ID</th>
                <th>Description</th>
                <th>Student Name</th>
                <th>Start Date</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php

            foreach ($allTasks as $task) {
                echo '
                    <tr class="task-row" data-start-date="' .
                    $task['start_date'] . '" data-deadline="' .
                    $task['deadline'] . '">
                        <td>' .
                            $task['id'] . TD_SEPARATOR .
                            $task['description'] . TD_SEPARATOR .
                            $task['student_name'] . TD_SEPARATOR .
                            $task['start_date'] . TD_SEPARATOR .
                            $task['deadline'] . TD_SEPARATOR .
                            $task['status'] .
                        '</td>
                        <td>
                            <button class="update-task" data-id="' . $task['id'] . '">Ongoing</button><br><br>
                            <button class="update-task" data-id="' . $task['id'] . '">Finished</button><br><br>
                            <button class="cancel-task" data-id="' . $task['id'] . '">Cancel</button>
                        </td>
                    </tr>';
            }

            ?>
        </table>
    </section>

    <section id="requested-tasks">
        <hr><h2>Requested Tasks (Unassigned)</h2><hr></hr>
        <table border="1">
            <caption></caption>
            <tr>
                <th>Task ID</th>
                <th>Description</th>
                <th>Status</th>
            </tr>

            <?php

            foreach ($requestedTasks as $task) {
                echo '
                    <tr class="task-row">
                        <td>' .
                            $task['id'] . TD_SEPARATOR .
                            $task['description'] . TD_SEPARATOR .
                            $task['status'] .
                        '</td>
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
<script src=../../API/sweetalert2.all.min.js"></script>
<link href="../../API/dark.css" rel="stylesheet">
<script src="../../API/jquery-3.7.1.min.js"></script>
<link href="../../API/Nav.css" rel="stylesheet">
<script>
    $(document).ready(function() {
        $(".update-task").click(function() {
            var taskId = $(this).data("id");
            var action = $(this).text();
        if (confirm("Are you sure you want to update the status of this task?")) {
            $.ajax({
                url: "../../Actions/HeadOffice/UpdateTaskStatus.php",
                method: "POST",
                data: { taskId: taskId, action: action },
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                    alert("An error occurred while updating the status of the task.");
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
                url: '../../Actions/HeadOffice/CancelTask.php',
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
})
</script>
