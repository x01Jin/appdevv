<?php

require_once 'db.php';

$profilePictureDirectory = 'profile_pictures/';

$errors = [];

function uploadProfilePicture(&$errors) {
    global $profilePictureDirectory;

    if (!file_exists($profilePictureDirectory)) {
        mkdir($profilePictureDirectory, 0755, true);
    }

    if (empty($_FILES['profile_picture']['name'])) {
        return null;
    }

    $profilePictureName = $_FILES['profile_picture']['name'];
    $profilePictureTmpName = $_FILES['profile_picture']['tmp_name'];

    $uniqueProfilePictureName = uniqid() . '_' . $profilePictureName;
    $destinationPath = $profilePictureDirectory . $uniqueProfilePictureName;

    if (move_uploaded_file($profilePictureTmpName, $destinationPath)) {
        return $uniqueProfilePictureName;
    } else {
        $errors[] = 'Failed to upload the profile picture.';
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = $_POST['role'];
    $program = $_POST['program'];
    $id_number = $_POST['id_number'];
    
    $profilePictureFileName = uploadProfilePicture($errors);

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, email, password,
                role, program, id_number, profile_picture)
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
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Registration failed. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="API/sweetalert2.all.min.js"></script>
    <link href="API/dark.css" rel="stylesheet">
    <script src="API/jquery-3.7.1.min.js"></script>
    <title>Registration</title>
    <style>
        body {
            background-image: url('assets/taskitbg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
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

        .error {
            color: red;
        }

        .form-table {
            width: 35%;
            margin: 0 auto;
        }

        .form-table th, .form-table td {
            text-align: left;
            padding: 5px;
        }

        .form-table input {
            width: 100%;
            padding: 5px;
        }

        .form-table select {
            width: 105%;
            padding: 5px;
        }

        .form-table button {
            background-color: #007BFF;
            color: white;
            padding: 5px;
            border: none;
            cursor: pointer;
            width: 103%;
        }
        
        .button-container {
            margin: 20px auto;
        }
    </style>
</head>
<body>
<div class="logo">
    <img src="assets/logo.png" alt="Company Logo">
</div>

<h1>Registration</h1>
<?php
if (!empty($errors)) {
    echo '<div class="error">';
    foreach ($errors as $error) {
        echo $error . '<br>';
    }
    echo '</div>';
}
?>

<table class="form-table">
    <caption></caption>
    <form action="registration.php" method="POST" enctype="multipart/form-data">
        <tr>
            <th scope="row"><label for="full_name">Full Name:</label></th>
            <td><input type="text" id="full_name" name="full_name" required></td>
        </tr>
        <tr>
            <th scope="row"><label for="email">Email:</label></th>
            <td><input type="email" id="email" name="email" required></td>
        </tr>
        <tr>
            <th scope="row"><label for="program">Program:</label></th>
            <td><input type="text" id="program" name="program"></td>
        </tr>
        <tr>
            <th scope="row"><label for="id_number">ID Number:</label></th>
            <td><input type="text" id="id_number" name="id_number"></td>
        </tr>
        <tr>
            <th scope="row"><label for="password">Password:</label></th>
            <td><input type="password" id="password" name="password" required></td>
        </tr>
        <tr>
            <th scope="row"><label for="confirm_password">Confirm Password:</label></th>
            <td><input type="password" id="confirm_password" name="confirm_password" required></td>
        </tr>
        <tr>
            <th scope="row"><label for="profile_picture">Profile Picture:</label></th>
            <td><input type="file" id="profile_picture" name="profile_picture"></td>
        </tr>
        <tr>
            <th scope="row"><label for="role">Role:</label></th>
            <td>
                <select id="role" name="role" required>
                    <option value="student">Student</option>
                    <option value="adviser">Adviser</option>
                    <option value="headoffice">Head Office</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="button-container">
                    <button type="submit" id="register">Register</button>
                </div>
            </td>
        </tr>
    </form>
</table>

<br>
<a href="index.php" style="color:white;"><b>Have an Account? Log in</b></a>
</body>
</html>

<script>
  $('#register').on('click', function(event) {
    event.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: 'Do you want to create an account?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Confirm',
      cancelButtonText: 'Cancel',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire(
          'Account Created!',
          'Your account has been created successfully.',
          'success'
        ).then(() => {
          $('form').submit();
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire(
          'Cancelled',
          'Account creation has been cancelled.',
          'error'
        );
      }
    });
  });
</script>
