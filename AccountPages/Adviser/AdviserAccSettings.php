<?php

include_once "../../db.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "adviser") {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id_number = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$accountInfo = $stmt->fetch(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="../../API/sweetalert2.all.min.js"></script>
    <link href="../../API/dark.css" rel="stylesheet">
    <script src="../../API/jquery-3.7.1.min.js"></script>
    <link href="../../API/AccSettings.css" rel="stylesheet">
    <style>
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
    <title>(Adviser)Account Settings</title>
</head>
<body>
    <div class="thebox">
        <header>
            <h1>ACCOUNT SETTINGS</h1>
        </header>
    </div>

    <div class="content">
        <nav>
            <?php echo '
            <img src="../../profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">'
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

        <div class="UpdatePfp">
        <hr><h2>Change Profile Picture</h2><hr>
            <form method="POST" action="../../Settings/UpdatePfp.php" enctype="multipart/form-data">
                <div class="circular-preview">
                    <img id="image-preview" src="
                    <?php echo '../../profile_pictures/' . $accountInfo['profile_picture']; ?>
                    " alt="Profile Picture Preview">
                </div>
                <div class="file-input">
                    <input type="file" id="profile_picture" name="profile_picture" style="display: none;">
                    <label for="profile_picture" class="file-button">Choose File</label>
                    <button type="submit" name="update_pfp">Update Profile Picture</button>
                </div>
            </form>
        </div>

        <hr><h2>Change Password</h2><hr>
        <form method="POST" action="../../Settings/ChangePassword.php">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" required><br><br>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required><br><br>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" required><br><br>
            <button type="submit" name="change_password">Change Password</button>
        </form>
        
        <hr><h2>Update Account Information</h2><hr>
        <form method="POST" action="../../Settings/UpdateAccount.php">
            <?php echo '
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" value="' . $accountInfo["full_name"] . '" required><br>
            <br>
            <label for="email">Email:</label>
            <input type="email" name="email" value="' . $accountInfo["email"] . '" required><br>
            <br>
            <button type="submit" name="update_account">Update Account</button>'
            ?>
        </form>

        <footer>
            &copy; <?php echo date("Y"); ?> Task Management System By CroixTech
        </footer>
    </div>

    <div class="account-info">
        <h2>Current Account Information:</h2><br><br>
    <?php echo
        '<p><strong>Role:</strong> ' . $accountInfo["role"] . '</p>' . '<br>' .
        '<p><strong>ID Number:</strong> ' . $accountInfo["id_number"] . '</p>' . '<br>' .
        '<p><strong>Full Name:</strong> ' . $accountInfo["full_name"] . '</p>' . '<br>' .
        '<p><strong>Email:</strong> ' . $accountInfo["email"] . '</p>' . '<br>' .
        '<p><strong>Program:</strong> ' . $accountInfo["program"] . '</p>'
    ?>
    </div>
</body>
</html>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const profilePictureInput = document.getElementById("profile_picture");
    const imagePreview = document.getElementById("image-preview");

    profilePictureInput.addEventListener("change", function() {
        const file = profilePictureInput.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };

            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '../../profile_pictures/<?php echo $accountInfo['profile_picture']; ?>';
        }
    });
});
</script>
<?php
if (isset($_SESSION['successMessage'])) {
    echo '
    <script>
    Swal.fire({
        icon: "success",
        title: "' . $_SESSION['successMessage'] . '",
        showConfirmButton: false,
        timer: 1500
    });
    </script>';
    unset($_SESSION['successMessage']);
}
if (isset($_SESSION['errorMessage'])) {
    echo '
    <script>
    Swal.fire({
        icon: "error",
        title: "' . $_SESSION['errorMessage'] . '",
        showConfirmButton: false,
        timer: 1500
    });
    </script>';
    unset($_SESSION['errorMessage']);
}
