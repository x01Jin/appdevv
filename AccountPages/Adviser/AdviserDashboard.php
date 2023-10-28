<?php
define('DB_PATH', "../../db.php");
define('SWEETALERT_PATH', "../../API/sweetalert2.all.min.js");
define('TD_SEPARATOR', '</td><td>');

include_once DB_PATH;
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "adviser") {
    header("Location: ../../index.php");
    exit();
}

$errorMessage = $successMessage = "";

$sql = "SELECT id_number, full_name FROM users WHERE role = 'student'";
$students = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sqlOngoing = "SELECT id, description, student_name, start_date, deadline, status FROM tasks WHERE status = 'ongoing'";
$ongoingTasks = $pdo->query($sqlOngoing)->fetchAll(PDO::FETCH_ASSOC);

$sqlFinished = "SELECT id, description, student_name, start_date, deadline,
                status FROM tasks WHERE status = 'finished'";
$finishedTasks = $pdo->query($sqlFinished)->fetchAll(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="<?php echo SWEETALERT_PATH; ?>"></script>
    <link href="../../API/dark.css" rel="stylesheet">
    <script src="../../API/jquery-3.7.1.min.js"></script>
    <link href="../../API/Nav.css" rel="stylesheet">
    <title>(Adviser) Dashboard</title>
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
</head>
<body>
    <div class="content">
        <header>
            <h1>ADVISER DASHBOARD</h1>
        </header>
        <nav>
            <?php echo '
            <img src="../../profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">';
            ?>
            <ul>
                <li><a href="AdviserDashboard.php" style="color:white;"><b>Adviser Dashboard</b></a></li>
                <li><a href="TaskAssigner.php" style="color:white;"><b>Assign Task</b></a></li>
                <li><a href="AddStudent.php" style="color:white;"><b>Add Student</b></a></li>
                <li><a href="AdviserAccSettings.php" style="color:white;"><b>Account Settings</b></a></li>
            </ul>
            <form class="logout-form" method="POST" action="../../index.php">
                <button type="submit" name="logout">Logout</button>
            </form>
        </nav>
        <hr>
        <h2>Student List</h2>
        <hr>
        <table border="1">
            <caption>List of students</caption>
            <tr>
                <th>Full Name</th>
                <th>ID Number</th>
                <th>Action</th>
            </tr>
            <?php
            foreach ($students as $student) {
                $id_number = isset($student['id_number']) ? $student['id_number'] : 'N/A';
                echo '<tr>
                        <td>' . $student['full_name'] . '</td>
                        <td>' . $id_number . '</td>
                        <td>
                            <button class="remove-student" data-student-id="' . $id_number . '">Remove Student</button>
                        </td>
                    </tr>';
            }
            ?>
        </table>
        <section id="ongoing-tasks">
            <hr>
            <h2>Ongoing Tasks</h2>
            <hr>
            <table border="1">
                <caption>List of ongoing tasks</caption>
                <tr>
                    <th>Task ID</th>
                    <th>Description</th>
                    <th>Student Name</th>
                    <th>Start Date</th>
                    <th>Deadline</th>
                    <th>Status</th>
                </tr>
                <?php
                foreach ($ongoingTasks as $task) {
                    echo '<tr>
                            <td>' . $task['id'] . TD_SEPARATOR .
                            $task['description'] . TD_SEPARATOR .
                            $task['student_name'] . TD_SEPARATOR .
                            $task['start_date'] . TD_SEPARATOR .
                            $task['deadline'] . TD_SEPARATOR .
                            $task['status'] . '</td>
                        </tr>';
                }
                ?>
            </table>
        </section>
        <section id="finished-tasks">
            <hr>
            <h2>Finished Tasks</h2>
            <hr>
            <table border="1">
                <caption>List of finished tasks</caption>
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
                foreach ($finishedTasks as $task) {
                    echo '<tr>
                            <td>' . $task['id'] . TD_SEPARATOR .
                            $task['description'] . TD_SEPARATOR .
                            $task['student_name'] . TD_SEPARATOR .
                            $task['start_date'] . TD_SEPARATOR .
                            $task['deadline'] . TD_SEPARATOR .
                            $task['status'] . '</td>
                            <td>
                                <button class="remove-finished-task" data-task-id="' . $task['id'] . '">Remove</button>
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
</body>
</html>
<script>
    $(document).ready(function() {
        $('.remove-student').on('click', function () {
            const studentId = $(this).data('student-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'Deleting a student will also delete their associated tasks. Do you want to continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../../Actions/Adviser/RemoveStudent.php', { delete_student: studentId }, function (data) {
                        if (data.success) {
                            Swal.fire(
                                'Student Deleted!',
                                data.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                data.message,
                                'error'
                            );
                        }
                    }, 'json');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'Student deletion has been cancelled.',
                        'info'
                    );
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.remove-finished-task').on('click', function () {
            const taskId = $(this).data('task-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to remove this finished task?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../../Actions/Adviser/DeleteFinished.php', { task_id: taskId }, function (data) {
                        if (data.success) {
                            Swal.fire(
                                'Task Removed!',
                                data.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                data.message,
                                'error'
                            );
                        }
                    }, 'json');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelled',
                        'Task removal has been cancelled.',
                        'info'
                    );
                }
            });
        });
    });
</script>
