<?php

include_once "db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: index.php");
    exit();
}

$errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_employee"])) {
    $employeeFullName = $_POST["employee_full_name"];
    $employeeEmail = $_POST["employee_email"];
    $employeePassword = password_hash($_POST["employee_password"], PASSWORD_DEFAULT);
    $employeeProgram = $_POST["employee_program"];
    $employeeIdNumber = $_POST["employee_id_number"];
    
    $profilePictureDirectory = "profile_pictures/";
    if (!file_exists($profilePictureDirectory)) {
        mkdir($profilePictureDirectory, 0755, true);
    }
    
    $profilePictureName = $_FILES["employee_profile_picture"]["name"];
    $profilePictureTmpName = $_FILES["employee_profile_picture"]["tmp_name"];
    
    if (!empty($profilePictureName)) {
        $uniqueProfilePictureName = uniqid() . '_' . $profilePictureName;
        $destinationPath = $profilePictureDirectory . $uniqueProfilePictureName;
        if (move_uploaded_file($profilePictureTmpName, $destinationPath)) {
            $profilePicturePath = $destinationPath;
        } else {
            $errorMessage = "Error uploading profile picture.";
        }
    } else {
        $profilePicturePath = "profile_pictures/default.jpg";
    }
    
    $checkUserSql = "SELECT COUNT(*) FROM users WHERE full_name = ? OR email = ?";
    $stmt = $pdo->prepare($checkUserSql);
    $stmt->execute([$employeeFullName, $employeeEmail]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $errorMessage = "Full name or email already exists. Please choose a different full name or email.";
    } else {
        $insertEmployeeSql = "
        INSERT INTO users (full_name, email, password, role, program, id_number, profile_picture)
        VALUES (?, ?, ?, 'employee', ?, ?, ?)";
        $stmt = $pdo->prepare($insertEmployeeSql);
        if ($stmt->execute([$employeeFullName, $employeeEmail,
        $employeePassword, $employeeProgram,
        $employeeIdNumber, $profilePicturePath])) {
            $successMessage = "Employee added successfully.";
            header("Location: adminaddemployee.php");
            exit();
        } else {
            $errorMessage = "Error adding employee. Please try again.";
        }
    }
}

include_once "pfpfunc.php";

echo "<title>(Admin)Add Employee</title>";

echo '
<div class="content">

<header>
<h1>ADD EMPLOYEE</h1>
</header>
<br>';

echo '
<nav>
<img src="profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">
<ul>
<li><a href="admindashboard.php" style="color:white;"><b>Admin Dashboard</b></a></li>
<li><a href="admintaskdeployer.php" style="color:white;"><b>Deploy Task</b></a></li>
<li><a href="adminaddemployee.php" style="color:white;"><b>Add Employee</b></a></li>
<li><a href="adminaccountsettings.php" style="color:white;"><b>Account Settings</b></a></li>
</ul>
<form method="POST" action="index.php">
<button type="submit" name="logout">Logout</button>
</form>
</nav>';

echo '
<form method="POST" action="adminaddemployee.php" enctype="multipart/form-data">
<label for="employee_full_name">Full Name:</label><br><br>
<input type="text" id="employee_full_name" name="employee_full_name" required><br><br>
<label for="employee_email">Email:</label><br><br>
<input type="email" id="employee_email" name="employee_email" required><br><br>
<label for="employee_program">Program:</label><br><br>
<input type="text" id="employee_program" name="employee_program" required><br><br>
<label for="employee_id_number">ID Number:</label><br><br>
<input type="text" id="employee_id_number" name="employee_id_number" required><br><br>
<label for="employee_password">Password:</label><br><br>
<input type="password" id="employee_password" name="employee_password" required><br><br>
<label for="employee_profile_picture">Profile Picture:</label><br><br>
<input type="file" id="employee_profile_picture" name="employee_profile_picture"><br><br>
<button type="submit" name="add_employee">Add Employee</button>
</form>';

if (isset($successMessage)) {
    echo '<div style="color: green;">' . $successMessage . '</div>';
} else {
    echo '<div style="color: red;">' . $errorMessage . '</div>';
}

echo '
<footer>
&copy; ' . date("Y") . ' Task Management System By CroixTech
</footer>
</div>';

echo "
<style>
body {
    background-image: url('assets/admin.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    font-family: Arial, sans-serif;
    text-align: center;
    color: lightskyblue;
}

.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: 20px auto;
    display: block;
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
    display: ;
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
";

echo '
<div class="task-preview"></div>';

echo '
<div class="calendar-popup"></div>';

echo '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="script.js"></script>';
