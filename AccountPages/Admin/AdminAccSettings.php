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

include_once "../../Settings/PfpFunc.php" ;

echo "<title>(Admin)Account Settings</title>";

echo '
<div class="thebox">

<header>
<h1>ACCOUNT SETTINGS</h1>
</header>

</div>';

echo '

<div class="content">

<nav>
<img src="../../profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">
<ul>
<li><a href="AdminDashboard.php" style="color:white;"><b>Admin Dashboard</b></a></li>
<li><a href="TaskDeployer.php" style="color:white;"><b>Deploy Task</b></a></li>
<li><a href="AddEmployee.php" style="color:white;"><b>Add Employee</b></a></li>
<li><a href="AdminAccSettings.php" style="color:white;"><b>Account Settings</b></a></li>
</ul>
<form method="POST" action="../../index.php">
<button type="submit" name="logout">Logout</button>
</form>
</nav>';

echo '
<hr><h2>Change Profile Picture</h2><hr>
<form method="POST" action="../../Settings/UpdatePfp.php" enctype="multipart/form-data">
<input type="file" name="profile_picture" accept="image/*" required>
<button type="submit" name="upload">Upload</button>
</form>';

echo '
<div class="UpdatePfp">';

echo '
<hr><h2>Update Account Information</h2><hr>
<form method="POST" action="../../Settings/UpdateAccount.php">
    <label for="full_name">Full Name:</label><br><br>
    <input type="text" name="full_name" value="' . $accountInfo["full_name"] . '" required><br>
    <br>
    <label for="email">Email:</label><br><br>
    <input type="email" name="email" value="' . $accountInfo["email"] . '" required><br>
    <br>
    <button type="submit" name="update_account">Update Account</button>
</form>';

echo '</div>';

if (!empty($errorMessage)) {
    echo '<div id="dialog" title="Error">' . $errorMessage . '</div>';
    unset($_SESSION["errorMessage"]);
} elseif (!empty($successMessage)) {
    echo '<div id="dialog" title="Success">' . $successMessage . '</div>';
    unset($_SESSION["successMessage"]);
}

?>

<footer>
&copy; <?php echo date("Y"); ?> Task Management System By CroixTech
</footer>

<?php

echo '</div>';

echo
'<div class="account-info">' .
'<h2>Current Account Information:</h2><br><br>' .
'<p><strong>Role:</strong> ' . $accountInfo["role"] . '</p>' . '<br>' .
'<p><strong>ID Number:</strong> ' . $accountInfo["id_number"] . '</p>' . '<br>' .
'<p><strong>Full Name:</strong> ' . $accountInfo["full_name"] . '</p>' . '<br>' .
'<p><strong>Email:</strong> ' . $accountInfo["email"] . '</p>' . '<br>' .
'<p><strong>Program:</strong> ' . $accountInfo["program"] . '</p>' .

'<div>';

echo "
<style>
body {
    background-image: url('../../assets/admin.jpg');
    background-size: cover;
    background-repeat: no-repeat;
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
";

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../../script.js"></script>
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
