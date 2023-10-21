<?php

include_once "../../db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "adviser") {
    header("Location: ../../index.php");
    exit();
}

$errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_student"])) {
    $studentFullName = $_POST["student_full_name"];
    $studentEmail = $_POST["student_email"];
    $studentPassword = password_hash($_POST["student_password"], PASSWORD_DEFAULT);
    $studentProgram = $_POST["student_program"];
    $studentIdNumber = $_POST["student_id_number"];
    
    $profilePictureDirectory = "../../profile_pictures/";
    if (!file_exists($profilePictureDirectory)) {
        mkdir($profilePictureDirectory, 0755, true);
    }
    
    $profilePictureName = $_FILES["student_profile_picture"]["name"];
    $profilePictureTmpName = $_FILES["student_profile_picture"]["tmp_name"];
    
    if (!empty($profilePictureName)) {
        $uniqueProfilePictureName = uniqid() . '_' . $profilePictureName;
        $destinationPath = $profilePictureDirectory . $uniqueProfilePictureName;
        if (move_uploaded_file($profilePictureTmpName, $destinationPath)) {
            $profilePicturePath = $destinationPath;
        } else {
            $errorMessage = "Error uploading profile picture.";
        }
    } else {
        $profilePicturePath = "../../profile_pictures/default.jpg";
    }
    
    $checkUserSql = "SELECT COUNT(*) FROM users WHERE full_name = ? OR email = ?";
    $stmt = $pdo->prepare($checkUserSql);
    $stmt->execute([$studentFullName, $studentEmail]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $errorMessage = "Full name or email already exists. Please choose a different full name or email.";
    } else {
        $insertStudentSql = "
        INSERT INTO users (full_name, email, password, role, program, id_number, profile_picture)
        VALUES (?, ?, ?, 'student', ?, ?, ?)";
        $stmt = $pdo->prepare($insertStudentSql);
        if ($stmt->execute([$studentFullName, $studentEmail,
        $studentPassword, $studentProgram,
        $studentIdNumber, $profilePicturePath])) {
            $_SESSION["successMessage"] = "Student added successfully.";
            header("Location: AddStudent.php");
            exit();
        } else {
            $errorMessage = "Error adding student. Please try again.";
        }
    }
}

include_once "../../Settings/PfpFunc.php";

?>

<title>(Admin)Add Student</title>

<div class="content">
<header>
    <h1>ADD STUDENT</h1>
</header>
<br>
<nav>
    <?php
    echo '<img src="../../profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">'
    ?>
    <ul>
        <li><a href="AdminDashboard.php" style="color:white;"><b>Admin Dashboard</b></a></li>
        <li><a href="TaskDeployer.php" style="color:white;"><b>Deploy Task</b></a></li>
        <li><a href="AddStudent.php" style="color:white;"><b>Add Student</b></a></li>
        <li><a href="AdminAccSettings.php" style="color:white;"><b>Account Settings</b></a></li>
    </ul>
    <form method="POST" action="../../index.php">
        <button type="submit" name="logout">Logout</button>
    </form>
</nav>

<?php
if (isset($_SESSION["successMessage"])) {
    echo '<div style="color: green;">' . $_SESSION["successMessage"] . '</div>';
    unset($_SESSION["successMessage"]);
} elseif (isset($errorMessage)) {
    echo '<div style="color: red;">' . $errorMessage . '</div>';
}
?>

<form method="POST" action="AddStudent.php" enctype="multipart/form-data">
    <label for="student_full_name">Full Name:</label><br><br>
    <input type="text" id="student_full_name" name="student_full_name" required><br><br>
    <label for="student_email">Email:</label><br><br>
    <input type="email" id="student_email" name="student_email" required><br><br>
    <label for="student_program">Program:</label><br><br>
    <input type="text" id="student_program" name="student_program" required><br><br>
    <label for="student_id_number">ID Number:</label><br><br>
    <input type="text" id="student_id_number" name="student_id_number" required><br><br>
    <label for="student_password">Password:</label><br><br>
    <input type="password" id="student_password" name="student_password" required><br><br>
    <label for="student_profile_picture">Profile Picture:</label><br><br>
    <input type="file" id="student_profile_picture" name="student_profile_picture"><br><br>
    <button type="submit" name="add_student">Add Student</button>
</form>

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
    margin-bottom: 10px;
}

nav ul li a {
    text-decoration: none;
    color: dark charcoal;
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
