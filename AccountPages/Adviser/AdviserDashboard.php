<?php

include_once "../../db.php";

define('TD_SEPARATOR', '</td><td>');

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "adviser") {
    header("Location: ../../index.php");
    exit();
}
$errorMessage = $successMessage = "";

function deleteTasks($studentId) {
    global $pdo;
    $sql = "DELETE FROM tasks WHERE student_id = :studentId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':studentId', $studentId);
    $stmt->execute();
}

function deleteStudent($studentId) {
    global $pdo;
    $sql = "DELETE FROM users WHERE id_number = :studentId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':studentId', $studentId);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_student"])) {
    $studentId = $_POST["delete_student"];
    $sqlCheckTasks = "SELECT COUNT(*) FROM tasks WHERE student_id = :studentId";
    $stmtCheckTasks = $pdo->prepare($sqlCheckTasks);
    $stmtCheckTasks->bindParam(':studentId', $studentId);
    $stmtCheckTasks->execute();
    $taskCount = $stmtCheckTasks->fetchColumn();
    if ($taskCount > 0) {
        deleteTasks($studentId);
    }
    deleteStudent($studentId);
    $successMessage = "Student deleted successfully.";
}

$sql = "SELECT id_number, full_name FROM users WHERE role = 'student'";
$students = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sqlOngoing = "SELECT id, description, student_name,
                start_date, deadline, status FROM tasks WHERE status IN ('ongoing')";
$ongoingTasks = $pdo->query($sqlOngoing)->fetchAll(PDO::FETCH_ASSOC);

$sqlFinished = "SELECT id, description, student_name,
                start_date, deadline, status FROM tasks WHERE status = 'finished'";
$finishedTasks = $pdo->query($sqlFinished)->fetchAll(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="../../API/sweetalert2.all.min.js"></script>
    <link href="../../API/dark.css" rel="stylesheet">
    <script src="../../API/jquery-3.7.1.min.js"></script>
    <title>(Adviser) Dashboard</title>
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
            <form method="POST" action="../../index.php">
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
                echo '
                    <tr>
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
                    echo '
                        <tr>
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
                    echo '
                        <tr>
                            <td>' . $task['id'] . TD_SEPARATOR .
                            $task['description'] . TD_SEPARATOR .
                            $task['student_name'] . TD_SEPARATOR .
                            $task['start_date'] . TD_SEPARATOR .
                            $task['deadline'] . TD_SEPARATOR .
                            $task['status'] . '</td>
                            <td>
                                <button class="remove-finished-task" data-task-id="' . $task['id'] . '">
                                    Remove
                                </button>
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
</script>
