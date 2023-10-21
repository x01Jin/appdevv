<?php

include_once "../../db.php";

define('TD_SEPARATOR', '</td><td>');

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "headoffice") {
    header("Location: ../../index.php");
    exit();
}

$errorMessage = $successMessage = "";

$sqlTasks = "SELECT id, description, employee_name, start_date, deadline, status
              FROM tasks WHERE status IN ('ongoing', 'finished')";
$allTasks = $pdo->query($sqlTasks)->fetchAll(PDO::FETCH_ASSOC);

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
        </ul>
        <form method="POST" action="../../index.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    </nav>

    <section id="ongoing-tasks">
        <hr><h2>Requested Tasks</h2><hr></hr>
        <table border="1">
            <caption>List of Requested tasks</caption>
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
                            $task['employee_name'] . TD_SEPARATOR .
                            $task['start_date'] . TD_SEPARATOR .
                            $task['deadline'] . TD_SEPARATOR .
                            $task['status'] .
                        '</td>
                        <td>
                            <button class="update-task" data-id="' . $task['id'] . '">Ongoing</button>
                            <button class="update-task" data-id="' . $task['id'] . '">Finalize</button>
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
        $(".update-task").click(function() {
            var taskId = $(this).data("id");
            var action = $(this).text();

            $.ajax({
                url: "../../Actions/HeadOffice/UpdateTaskStatus.php",
                method: "POST",
                data: { taskId: taskId, action: action },
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    
                }
            });
        });
    });
</script>
