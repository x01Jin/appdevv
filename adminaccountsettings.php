<?php

include_once "db.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: appdev.php");
    exit();
}

$errorMessage = $successMessage = "";

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id_number = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$accountInfo = $stmt->fetch(PDO::FETCH_ASSOC);

include_once "pfpfunc.php" ;

echo "<title>(Admin)Account Settings</title>";

echo '
<div class="content">

<header>
<h1>ACCOUNT SETTINGS</h1>
</header>';

echo '
<nav>
<img src="profile_pictures/' . $profilePicture . '" alt="Profile Picture" class="profile-picture">
<ul>
<li><a href="admindashboard.php" style="color:white;"><b>Admin Dashboard</b></a></li>
<li><a href="admintaskdeployer.php" style="color:white;"><b>Deploy Task</b></a></li>
<li><a href="adminaddemployee.php" style="color:white;"><b>Add Employee</b></a></li>
<li><a href="adminaccountsettings.php" style="color:white;"><b>Account Settings</b></a></li>
</ul>
<form method="POST" action="appdev.php">
<button type="submit" name="logout">Logout</button>
</form>
</nav>';

echo '
<hr><h2>Change Profile Picture</h2><hr>
<form method="POST" action="updatepfp.php" enctype="multipart/form-data">
<input type="file" name="profile_picture" accept="image/*" required>
<button type="submit" name="upload">Upload</button>
</form>';

echo '
<div class="updatepfp">';

if (!empty($errorMessage)) {
    echo '<p class="error">' . $errorMessage . '</p>';
} elseif (!empty($successMessage)) {
    echo '<p class="success">' . $successMessage . '</p>';
}

echo '</div>';

echo '
<footer>
&copy; <?php echo date("Y"); ?> Task Management System By CroixTech
</footer>

</div>';

echo
'<div class="account-info">' .

'<h2>Current Account Information:</h2>' .
'<p><strong>Role:</strong> ' . $accountInfo["role"] . '</p>' .
'<p><strong>ID Number:</strong> ' . $accountInfo["id_number"] . '</p>' .
'<p><strong>Full Name:</strong> ' . $accountInfo["full_name"] . '</p>' .
'<p><strong>Email:</strong> ' . $accountInfo["email"] . '</p>' .
'<p><strong>Program:</strong> ' . $accountInfo["program"] . '</p>' .

'<div>';

echo "
<style>
body {
    background-image: url('assets/admin.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    font-family: Arial, sans-serif;
    text-align: center;
    color: white;
}

.account-info {
    text-align: left;
    margin-top: 50px;
    float: right;
    width: 300px;
    padding: 20px;
    background-color: rgba(51, 51, 51, 0.8);
    color: white;
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

.error {
    color: red;
}

.success {
    color: green;
}
</style>
";

echo '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="script.js"></script>';
