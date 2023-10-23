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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            margin: 0;
            padding: 0;
        }

        .logo img {
            width: 80%;
            max-width: 400px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        form {
            max-width: 300px;
            margin: 0 auto;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        form label {
            display: block;
            text-align: center;
            font-weight: bold;
        }

        form input {
            width: 90%;
            padding: 5px;
            margin: 8px 0;
            border: 1px solid darkblue;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        form input:focus {
            border: 1px solid lightskyblue;
            outline: none;
        }

        form button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
        }

        .file-button {
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

        .file-button:hover {
            background: wheat;
        }

        select {
            width: 90%;
            padding: 5px;
            margin: 8px 0;
            border: 1px solid darkblue;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            background: white;
            color: black;
            appearance: none;
            outline: none;
            text-align: center;
        }

        select:hover {
            border: 1px solid lightskyblue;
        }

        .circular-preview {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            border: 1px solid darkblue;
        }

        .circular-preview img {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }
    </style>
</head>
<body>
    <br>
    <div class="logo">
        <img src="assets/logo.png" alt="Company Logo">
    </div>
    <br><br>
    <form action="registration.php" method="POST" enctype="multipart/form-data">
        <label for="full_name"><b>Full Name:</b></label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="email"><b>Email:</b></label>
        <input type="email" id="email" name="email" required>

        <label for="program"><b>Program:</b></label>
        <input type="text" id="program" name="program">

        <label for="id_number"><b>ID Number:</b></label>
        <input type="text" id="id_number" name="id_number">

        <label for="password"><b>Password:</b></label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password"><b>Confirm Password:</b></label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label for="profile_picture"><b>Profile Picture:</b></label>
        <div class="circular-preview">
            <img id="image-preview" src="" alt="Profile Picture Preview" style="display: none;">
        </div>
        <div class="file-input">
            <input type="file" id="profile_picture" name="profile_picture" style="display: none;">
            <label for="profile_picture" class="file-button">Choose File</label>
        </div>

        <label for="role"><b>Role:</b></label>
        <select id="role" name="role" required>
            <option value="student">Student</option>
            <option value="adviser">Adviser</option>
            <option value="headoffice">Head Office</option>
        </select>
        <br><br>
        <button type="submit"id="register">Register</button>
    </form>
    <br>

    <a href="index.php" style="color:white;"><b>Have an Account? Log in</b></a><br><br>
</body>
</html>
<script>
  $('#register').on('click', function(event) {
    event.preventDefault();
    var fullName = $('#full_name').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var confirmPassword = $('#confirm_password').val();

    if (fullName === '' || email === '' || password === '' || confirmPassword === '') {
      Swal.fire({
        title: 'Warning',
        text: 'Please fill in all required fields.',
        icon: 'warning'
      });
    } else if (password !== confirmPassword) {
      Swal.fire({
        title: 'Password Mismatch',
        text: 'The passwords do not match. Please make sure they match.',
        icon: 'error'
      });
    } else {
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
    }
  });

  $('#confirm_password').on('keyup', function() {
    Swal.close();
  });
</script>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image-preview').attr('src', e.target.result);
                $('#image-preview').show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#profile_picture').on('change', function() {
        previewImage(this);
    });
</script>
