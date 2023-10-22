<?php

include_once "db.php";

session_start();

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT id_number, full_name, password, role FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        initializeSession($user);
        redirectUser($user["role"]);
    } else {
        $error_message = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TASK.it</title>
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
            width: 700px;
            height: auto;
        }
    </style>
</head>
<body>
    <br><br><br><br><br>
    <div class="logo">
        <img src="assets/logo.png" alt="Company Logo">
    </div>
    <br><br>
    <form action="index.php" method="POST">
        <label for="email"><b>Email:</b></label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password"><b>Password:</b></label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Log In</button>
    </form>
    <br><br>
    <?php
    if (isset($error_message)) {
        echo '<div style="color: red;">' . $error_message . '</div>';
    }
    ?>

    <a href="registration.php" style="color:white;"><b>No Account? Register</b></a>
</body>
</html>

<?php
function initializeSession($user) {
    session_start();
    $_SESSION["user_id"] = $user["id_number"];
    $_SESSION["username"] = $user["full_name"];
    $_SESSION["user_role"] = $user["role"];
}

function redirectUser($role) {
    $redirectLocation = "AccountPages/" . ucfirst($role) . "/" . $role . "Dashboard.php";
    header("Location: $redirectLocation");
    exit();
}
?>
