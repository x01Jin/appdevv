<?php

include_once "../../db.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "student") {
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
    <link href="../../API/dark.css" rel="stylesheet">
    <script src="../../API/jquery-3.7.1.min.js"></script>
    <title>(Adviser)Account Settings</title>
    <link href="../../API/AccSettings.css" rel="stylesheet">
    <link href="../../API/Nav.css" rel="stylesheet">
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
                <li><a href="StudentDashboard.php" style="color:white;"><b>Student Dashboard</b></a></li>
                <li><a href="StudentAccSettings.php" style="color:white;"><b>Account Settings</b></a></li>
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
