<?php

include_once "../../db.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "adviser") {
    header("Location: ../../index.php");
    exit();
}

$successMessage = $_SESSION["successMessage"] ?? "";
$errorMessage = $_SESSION["errorMessage"] ?? "";

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id_number = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$accountInfo = $stmt->fetch(PDO::FETCH_ASSOC);

include_once "../../Settings/PfpFunc.php";

?>

<title>(Adviser)Account Settings</title>

<?php
if (!empty($errorMessage)) {
    echo '<div id="dialog" title="Error">' . $errorMessage . '</div>';
    unset($_SESSION["errorMessage"]);
} elseif (!empty($successMessage)) {
    echo '<div id="dialog" title="Success">' . $successMessage . '</div>';
    unset($_SESSION["successMessage"]);
}
?>

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
        <form method="POST" action="../../index.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    </nav>

    <div class="UpdatePfp">
    <hr><h2>Change Profile Picture</h2><hr>
        <form method="POST" action="../../Settings/UpdatePfp.php" enctype="multipart/form-data">
            <input type="file" name="profile_picture" accept="image/*" required>
            <button type="submit" name="upload">Upload</button>
        </form>
    </div>

    <hr><h2>Change Password</h2><hr>
    <form method="POST" action="../../Settings/ChangePassword.php">
        <label for="current_password">Current Password:</label><br><br>
        <input type="password" name="current_password" required><br><br>
        <label for="new_password">New Password:</label><br><br>
        <input type="password" name="new_password" required><br><br>
        <label for="confirm_password">Confirm New Password:</label><br><br>
        <input type="password" name="confirm_password" required><br><br>
        <button type="submit" name="change_password">Change Password</button>
    </form>
    
    <hr><h2>Update Account Information</h2><hr>
    <form method="POST" action="../../Settings/UpdateAccount.php">
        <?php echo '
        <label for="full_name">Full Name:</label><br><br>
        <input type="text" name="full_name" value="' . $accountInfo["full_name"] . '" required><br>
        <br>
        <label for="email">Email:</label><br><br>
        <input type="email" name="email" value="' . $accountInfo["email"] . '" required><br>
        <br>
        <button type="submit" name="update_account">Update Account</button>'
        ?>
    </form>

    <footer>
        &copy; <?php echo date("Y"); ?> Task Management System By CroixTech
    </footer>
</div>

<?php
echo
'<div class="account-info">' .
    '<h2>Current Account Information:</h2><br><br>' .
    '<p><strong>Role:</strong> ' . $accountInfo["role"] . '</p>' . '<br>' .
    '<p><strong>ID Number:</strong> ' . $accountInfo["id_number"] . '</p>' . '<br>' .
    '<p><strong>Full Name:</strong> ' . $accountInfo["full_name"] . '</p>' . '<br>' .
    '<p><strong>Email:</strong> ' . $accountInfo["email"] . '</p>' . '<br>' .
    '<p><strong>Program:</strong> ' . $accountInfo["program"] . '</p>' .
'</div>';
?>

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

    .account-info {
        text-align: left;
        width: 292px;
        height: 100%;
        padding: 20px;
        background-color: rgba(51, 51, 51, 0.5);
        color: white;
        position: fixed;
        top: 167px;
        right: 8;
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

    .thebox {
        margin-left: 200px;
        padding: 20px;
        background-color: rgba(51, 51, 51, 0.5);
        color: white;
    }

    .content {
        margin-left: 200px;
        margin-right: 332px;
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
        color: white;
        display: block;
        padding: 5px;
    }

    footer {
        background-color: rgba(51, 51, 51, 0.8);
        color: white;
        text-align: center;
        padding: 10px;
    }

    #dialog {
        display: none;
        padding: 10px;
        background-color: #333;
    }

    .ui-dialog {
        background-color: black;
        color: white;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 0px rgba(255, 255, 255, 0.2);
    }

    .ui-widget-overlay {
        background-color: rgba(10, 10, 10, 0.9) !important;
    }

    .ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
        float: none;
    }

    .ui-dialog .ui-dialog-buttonpane {
        text-align: center;
        padding: 5px;
    }

    .ui-dialog-buttonset button {
        background-color: white;
        color: black;
        padding: 8px 16px;
        border-radius: 5px;
        margin: 0 10px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(document).ready(function() {
    $("#dialog").dialog({
      autoOpen: false,
      modal: true,
      width: '400',
      height: '100',
      position: {
        my: "center",
        at: "center",
        of: window
      },
      buttons: {
        Close: function() {
          $(this).dialog("close");
        }
      }
    });

    <?php if (!empty($errorMessage) || !empty($successMessage)) : ?>
      $("#dialog").html('<?php echo addslashes($errorMessage . $successMessage); ?>');
      $("#dialog").dialog("option", "title", '<?php echo (!empty($errorMessage)) ? "Error" : "Success!"; ?>');
      $("#dialog").dialog("open");
      $(".ui-dialog-titlebar-close").remove();
    <?php endif; ?>
  });
</script>
