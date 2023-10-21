<?php

include_once "db.php";

$profilePictureDirectory = "profile_pictures/";

if (!file_exists($profilePictureDirectory)) {
    mkdir($profilePictureDirectory, 0755, true);
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $role = $_POST["role"];
    $program = $_POST["program"];
    $id_number = $_POST["id_number"];
    
    $profilePictureFileName = null;

    $profilePictureName = $_FILES["profile_picture"]["name"];
    $profilePictureTmpName = $_FILES["profile_picture"]["tmp_name"];
    
    if (!empty($profilePictureName)) {
        $uniqueProfilePictureName = uniqid() . '_' . $profilePictureName;
        $destinationPath = $profilePictureDirectory . $uniqueProfilePictureName;
        if (move_uploaded_file($profilePictureTmpName, $destinationPath)) {
            $profilePictureFileName = $uniqueProfilePictureName;
        } else {
            $errors[] = "Failed to upload the profile picture.";
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "
        INSERT INTO users
        (full_name, email, password, role, program, id_number, profile_picture)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $full_name, PDO::PARAM_STR);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->bindParam(3, $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(4, $role, PDO::PARAM_STR);
        $stmt->bindParam(5, $program, PDO::PARAM_STR);
        $stmt->bindParam(6, $id_number, PDO::PARAM_STR);
        $stmt->bindParam(7, $profilePictureFileName, PDO::PARAM_STR);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again later.";
        }
    }
}
?>


<div class="logo">
<img src="assets/logo.png" alt="Company Logo">
</div>

<h1>Registration</h1>
<?php
if (!empty($errors)) {
    echo '<div style="color: red;">';
    foreach ($errors as $error) {
        echo $error . '<br>';
    }
    echo '</div>';
}
?>

<form action="registration.php" method="POST" enctype="multipart/form-data">
    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name" name="full_name" required>
    </br></br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    </br></br>
    <label for="program">Program:</label>
    <input type="text" id="program" name="program">
    </br></br>
    <label for="id_number">ID Number:</label>
    <input type="text" id="id_number" name="id_number">
    </br></br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    </br></br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    </br></br>
    <label for="profile_picture">Profile Picture:</label>
    <input type="file" id="profile_picture" name="profile_picture">
    </br></br>
    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="student">Student</option>
        <option value="adviser">Adviser</option>
        <option value="headoffice">Head Office</option>
    </select>
    </br></br>
    <button type="submit">Register</button>
</form>

<a href="index.php" style="color:white;"><b>Have an Account? Log in</b></a>';

<style>
    body {
        background-image: url('assets/taskitbg.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        font-family: Arial, sans-serif;
        text-align: center;
        color: white;
    }

    .logo {
        text-align: center;
        margin-top: 20px;
    }

    .logo img {
        width: 500px;
        height: auto;
    }
</style>
