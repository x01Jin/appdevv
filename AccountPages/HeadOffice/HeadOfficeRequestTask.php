<?php

include_once "../../db.php";

define('TD_SEPARATOR', '</td><td');

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "headoffice") {
    header("Location: ../../index.php");
    exit();
}

$errorMessage = $successMessage = "";

$premadeTasks = [
    'Help in encoding of SAO',
    'Help in encoding of TES',
    'Help in encoding of ISO',
    'Help organizing papers',
    'Request personal Help',
];

include_once "../../Settings/PfpFunc.php";
?>

<title>(Head Office) Request Tasks</title>

<div class="content">
    <header>
        <h1>HEAD OFFICE REQUEST TASKS</h1>
    </header>

    <nav>
        <?php
        echo '<img src="../../profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">'
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

    <section id="request-tasks">
        <hr><h2>Available Tasks</h2><hr>
        <form method="POST">
            <table border="1">
                <caption></caption>
                <tr>
                    <th>Select</th>
                    <th>Task Description</th>
                </tr>

                <?php
                foreach ($premadeTasks as $index => $description) {
                    echo '<tr>';
                    echo '<td><input type="checkbox" name="task[]" value="' . $index . '"></td>';
                    echo '<td>' . $description . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <br>
            <button type="submit" name="deploy-tasks">Deploy Requests</button>
        </form>
    </section>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deploy-tasks'])) {
        if (isset($_POST['task'])) {
            $selectedTasks = $_POST['task'];

            foreach ($selectedTasks as $index) {
                $description = $premadeTasks[$index];
                $sql = "INSERT INTO tasks (description, student_id, status)
                        VALUES (:description, 'unassigned', 'requested')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':description' => $description]);
            }

            echo '<div class="success-message">Tasks have been successfully deployed.</div>';
        } else {
            echo '<div class="error-message">No tasks were selected for deployment.</div>';
        }
    }
    ?>

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
<link href="../../API/Nav.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#deploy-tasks").click(function() {
            var selectedTasks = [];

            $('input[name="task[]"]:checked').each(function() {
                selectedTasks.push($(this).val());
            });

            if (selectedTasks.length === 0) {
                alert("Please select at least one task to deploy.");
                return;
            }

            $.ajax({
                url: 'HeadOfficeRequestTasks.php',
                method: 'POST',
                data: {
                    task: selectedTasks
                },
                success: function(response) {
                    // Kahit hindi na to ata
                },
                error: function(error) {
                    console.error(error);
                    alert("An error occurred while deploying the tasks.");
                }
            });
        });
    });
</script>
