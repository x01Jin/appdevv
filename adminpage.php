<?php
include_once "db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: appdev.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deploy_task"])) {
    $taskDescription = $_POST["task_description"];
    $employeeId = $_POST["employee_id"];
    $startDate = $_POST["start_date"];
    $deadline = $_POST["deadline"];

    $insertTaskSql = "INSERT INTO tasks (description, employee_id, start_date, deadline) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertTaskSql);
    if ($stmt->execute([$taskDescription, $employeeId, $startDate, $deadline])) {
        $successMessage = "Task deployed successfully.";
    } else {
        $errorMessage = "Error deploying task: " . $stmt->errorInfo()[2];
    }
}


$sql = "SELECT id, username FROM users WHERE role = 'employee'";
$employees = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT id, description, employee_id, start_date, deadline FROM tasks";
$tasks = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Page</title>
    <style>
        body {
            background-image: url('assets/admin.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            text-align: center;
            color: #fff;
        }

        table {
            margin: 0 auto;
        }

        table th {
            color: #fff;
        }

        table td {
            color: #fff;
        }

        header {
            background-color: rgba(51, 51, 51, 0.5);
            color: white;
            text-align: center;
            padding: 20px;
        }

        nav {
            background-color: rgba(20, 20, 20, 0.8);
            color: white;
            padding: 10px;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
        }

        nav ul li {
            display: inline-block;
            margin-right: 20px;
        }

        main {
            padding: 20px;
        }

        footer {
            background-color: rgba(51, 51, 51, 0.8);
            color: #fff;
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
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <form method="POST" action="appdev.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    </header>
    <nav>
        <ul>
            <li><a href="#deploy" style="color:white;">Deploy Task</a></li>
            <li><a href="#employee-list" style="color:white;">Employee List</a></li>
            <li><a href="#task-list" style="color:white;">Task List</a></li>
            <li><a href="#add-employee" style="color:white;">Add Employee</a></li>
        </ul>
    </nav>
    <main>
        <section id="deploy">
        <hr></hr><h2>Deploy Task</h2><hr></hr>
            <form method="POST" action="adminpage.php">
                <label for="task_description">Task Description:</label>
                <input type="text" id="task_description" name="task_description" required></br></br>
                <label for="employee_id">Assign to Employee:</label>
                <select id="employee_id" name="employee_id" required></br></br>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['id']; ?>"><?php echo $employee['username']; ?></option>
                    <?php endforeach; ?>
                </select></br></br>
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required></br></br>
                <label for="deadline">Deadline:</label>
                <input type="date" id="deadline" name="deadline" required></br></br>
                <button type="submit" name="deploy_task">Deploy Task</button>
            </form>
        </section>

        <section id="employee-list">
        <hr></hr><h2>Employee List</h2><hr></hr>
            <table border="1">
            <caption>List of employees</caption>
                <tr>
                    <th>Employee ID</th>
                    <th>Username</th>
                </tr>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo $employee['id']; ?></td>
                        <td><?php echo $employee['username']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <section id="task-list">
        <hr></hr><h2>Task List</h2><hr></hr>
            <table border="1">
                <caption>List of tasks</caption>
                <tr>
                    <th>Task ID</th>
                    <th>Description</th>
                    <th>Employee ID</th>
                    <th>Start Date</th>
                    <th>Deadline</th>
                </tr>
                <?php foreach ($tasks as $task): ?>
                    <tr class=
                    "task-row" data-start-date="<?php echo $task['start_date'];
                    ?>
                    "data-deadline="<?php echo $task['deadline'];
                    ?>">
                        <td><?php echo $task['id']; ?></td>
                        <td><?php echo $task['description']; ?></td>
                        <td><?php echo $task['employee_id']; ?></td>
                        <td><?php echo $task['start_date']; ?></td>
                        <td><?php echo $task['deadline']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <section id="add-employee">
            <hr><h2>Add Employee</h2><hr>
            <form method="POST" action="adminpage.php">
                <label for="employee_username">Username:</label><br><br>
                <input type="text" id="employee_username" name="employee_username" required><br><br>
                <label for="employee_email">Email:</label><br><br>
                <input type="email" id="employee_email" name="employee_email" required><br><br>
                <label for="employee_password">Password:</label><br><br>
                <input type="password" id="employee_password" name="employee_password" required><br><br>
                <button type="submit" name="add_employee">Add Employee</button>
            </form>
        </section>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Task Management System By CroixTech
    </footer>

    <div class="task-preview"></div>

    <div class="calendar-popup">
        <div id="calendar"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(".task-row").hover(function () {
            var startDate = $(this).data("start-date");
            var deadline = $(this).data("deadline");
            var taskDescription = $(this).find("td:eq(1)").text();
            var taskPreview = "Task: " + taskDescription + "<br>Start Date: " + startDate + "<br>Deadline: " + deadline;

            var elementPosition = $(this).offset();
            var elementHeight = $(this).height();

            var popupTop = elementPosition.top - elementHeight - $(".task-preview").height() - 10;
            var popupLeft = elementPosition.left;

            $(".task-preview").html(taskPreview).css({
                "top": popupTop,
                "left": popupLeft
            }).show();
        }, function () {
            $(".task-preview").hide();
        });

        $("#calendar").datepicker({
            dateFormat: "yy-mm-dd",
            beforeShowDay: function (date) {
                var startDate = new Date($(".task-preview").find("Start Date").text());
                var deadline = new Date($(".task-preview").find("Deadline").text());

                if (date >= startDate && date <= deadline) {
                    return [true, "highlighted", "Task Duration"];
                } else {
                    return [true, "", ""];
                }
            }
        });

        $(".task-row").on("click", function () {
            $(".calendar-popup").css({
                "top": $(this).offset().top + $(this).height() + 10,
                "left": $(this).offset().left
            }).show();
        });

        $(document).on("click", function (e) {
            if (!$(e.target).closest(".calendar-popup").length) {
                $(".calendar-popup").hide();
            }
        });
    </script>
</body>
</html>
