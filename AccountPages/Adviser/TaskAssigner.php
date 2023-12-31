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

        $noTasksSelected = true;
        $unassignedStudent = false;
        $emptyStartDateOrDeadline = false;

        foreach ($assignedTasks as $taskId) {
            $studentId = $_POST['student'][$taskId];
            $startDate = $_POST['start_date'][$taskId];
            $deadline = $_POST['deadline'][$taskId];

            $noTasksSelected = false;

            if ($studentId === "") {
                $unassignedStudent = true;
            } elseif (empty($startDate) || empty($deadline)) {
                $emptyStartDateOrDeadline = true;
            }

            $sql = "UPDATE tasks SET student_id = :studentId, status = 'ongoing',
                start_date = :startDate, deadline = :deadline
                WHERE id = :taskId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':studentId' => $studentId,
                ':startDate' => $startDate,
                ':deadline' => $deadline,
                ':taskId' => $taskId
            ]);

            $sqlStudent = "SELECT full_name FROM users WHERE id_number = :studentId";
            $stmtStudent = $pdo->prepare($sqlStudent);
            $stmtStudent->execute([':studentId' => $studentId]);
            $studentName = $stmtStudent->fetchColumn();

            $sqlUpdateName = "UPDATE tasks SET student_name = :studentName WHERE id = :taskId";
            $stmtUpdateName = $pdo->prepare($sqlUpdateName);
            $stmtUpdateName->execute([':studentName' => $studentName, ':taskId' => $taskId]);
        }

        if ($noTasksSelected) {
            $errorMessage = 'No tasks were selected for assignment.';
        } elseif ($unassignedStudent) {
            $errorMessage = 'One or more tasks have an unassigned student.';
        } elseif ($emptyStartDateOrDeadline) {
            $errorMessage = 'One or more tasks have empty start date or deadline.';
        } else {
            $successMessage = 'Tasks have been successfully assigned.';
        }
    } else {
        $errorMessage = 'How did we get here?.';
    }
}

$sql = "SELECT id, description FROM tasks WHERE status = 'requested'";
$requestedTasks = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sqlStudents = "SELECT id_number, full_name FROM users WHERE role = 'student'";
$students = $pdo->query($sqlStudents)->fetchAll(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="../../API/sweetalert2.all.min.js"></script>
    <link href="../../API/dark.css" rel="stylesheet">
    <script src="../../API/jquery-3.7.1.min.js"></script>
    <title>(Adviser) Task Assigner</title>
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
            width: 100%;
            color: white;
            border-collapse: collapse;
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

        #assign-task {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #assign-task:hover {
            background-color: #45a049;
        }

        select {
            padding: 5px;
        }

        input[type="date"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
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

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            border: 1px solid darkblue;
            display: flex;
        }

        .profile-picture img {
            max-width: 100%;
            max-height: auto;
            object-fit: cover;
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

        .logout-form {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            box-shadow: none;
        }

        .logout-form button {
            display: inline-block;
            background: white;
            color: black;
            padding: 5px;
            border: 1px solid darkblue;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            width: 86%;
            margin: 9px;
        }

        .logout-form button:hover {
            background: wheat;
        }
    </style>
</head>
<body>
    <div class="content">
        <header>
            <h1>TASK ASSIGNER</h1>
        </header>

        <nav>
            <img src="../../profile_pictures/<?= $profilePicture ?>" alt="Profile Picture" class="profile-picture">
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

        <section id="assign-tasks">
            <hr><h2>Assign Tasks</h2><hr>
            <form method="POST" name="assign-tasks-form">
                <table border="1">
                    <caption><br><b>List of Tasks Requested By Registrar</b><br><br></caption>
                    <tr>
                        <th>Select</th>
                        <th>Task Description</th>
                        <th>Student</th>
                        <th>Start Date</th>
                        <th>Deadline</th>
                    </tr>
                    <?php foreach ($requestedTasks as $task) : ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="task[]" value="<?= $task['id'] ?>">
                            </td>
                            <td><?= $task['description'] ?></td>
                            <td>
                                <select name="student[<?= $task['id'] ?>]">
                                    <option value="">Unassigned</option>
                                    <?php foreach ($students as $student) : ?>
                                        <option value="<?= $student['id_number'] ?>">
                                            <?= $student['full_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="date" name="start_date[<?= $task['id'] ?>]"
                                        pattern="\d{4}-\d{2}-\d{2}"></td>
                            <td><input type="date" name="deadline[<?= $task['id'] ?>]"
                                        pattern="\d{4}-\d{2}-\d{2}"></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <br>
                <button type="submit" name="assign-tasks" id="assign-task">Assign Tasks</button>
            </form>
        </section>
        <br>
        <footer>
            &copy; <?= date("Y"); ?> Task Management System By CroixTech
        </footer>
    </div>
</body>
</html>
<?php if (!empty($successMessage)) : ?>
    <script>
        Swal.fire({
            title: 'Success',
            text: '<?= $successMessage ?>',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
<?php elseif (!empty($errorMessage)) : ?>
    <script>
        Swal.fire({
            title: 'Error',
            text: '<?= $errorMessage ?>',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>
<script>
    function showAlert(title, text, icon) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!empty($successMessage)) : ?>
            showAlert('Success', '<?= $successMessage ?>', 'success');
        <?php elseif (!empty($errorMessage)) : ?>
            showAlert('Error', '<?= $errorMessage ?>', 'error');
        <?php endif; ?>

        document.querySelector("form[name='assign-tasks-form']").addEventListener('submit', function (event) {
            const checkboxes = document.querySelectorAll("input[name='task[]']");
            let atLeastOneSelected = false;
            let unassignedSelected = false;
            let emptyDateSelected = false;

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    atLeastOneSelected = true;
                    const taskId = checkbox.value;
                    const studentSelect = document.querySelector(`select[name='student[${taskId}]']`);
                    const startDateInput = document.querySelector(`input[name='start_date[${taskId}]']`);
                    const deadlineInput = document.querySelector(`input[name='deadline[${taskId}]']`);

                    if (studentSelect.value === "") {
                        unassignedSelected = true;
                    }
                    if (startDateInput.value === "" || deadlineInput.value === "") {
                        emptyDateSelected = true;
                    }
                }
            });

            if (!atLeastOneSelected) {
                showAlert('Warning', 'No tasks were selected for assignment.', 'warning');
                event.preventDefault();
            } else if (unassignedSelected) {
                showAlert('Warning', 'One or more selected tasks are unassigned.', 'warning');
                event.preventDefault();
            } else if (emptyDateSelected) {
                showAlert('Warning', 'One or more selected tasks have empty start date or deadline.', 'warning');
                event.preventDefault();
            }
        });
    });
</script>
