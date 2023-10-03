<?php

include_once "db.php";

session_start();

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: appdev.php");
    exit();
}

echo "<title>TASK.it</title><br><br><br><br><br>";

echo '<div class="logo">
    <img src="assets/logo.png" alt="Company Logo">
</div><br><br>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT id_number, full_name, password, role FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        session_start();
        $_SESSION["user_id"] = $user["id_number"];
        $_SESSION["username"] = $user["full_name"];
        $_SESSION["user_role"] = $user["role"];

        if ($user["role"] == "admin") {
            header("Location: admindashboard.php");
            exit();
        } else {
            header("Location: employeedashboard.php");
            exit();
        }
    } else {
        $error_message = "Invalid email or password. Please try again.";
    }
}

echo '
<form action="appdev.php" method="POST">
<label for="email"><b>Email:</b></label></br>
<input type="email" id="email" name="email" required>
</br></br>
<label for="password"><b>Password:</b></label></br>
<input type="password" id="password" name="password" required>
</br></br>
<button type="submit">Log In</button>
</form>';

if (isset($error_message)) {
    echo '<div style="color: red;">' . $error_message . '</div>';
}

echo '<a href="registration.php" style="color:white;"><b>No Account? Registers</b></br>';

print"
<style>

body {
    background-image: url('assets/taskitbg.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    font-family: Arial, sans-serif;
    text-align: center;
    color: white;
}

header {
    background-color: white;
    color: black;
    text-align: center;
    padding: 20px;
}

main {
    padding: 20px;
}

.logo {
    text-align: center;
    margin-top: 20px;
}

.logo img {
    width: 700px;
    height: auto;
}

</style>
";
