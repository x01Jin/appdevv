<?php

include_once "db.php";

print"
Task Management System</br>
<hr></hr>
</br>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["user_role"] = $user["role"];

        if ($user["role"] == "admin") {
            header("Location: admindashboard.php");
            exit();
        } else {
            header("Location: employeedashboard.php");
            exit();
        }
    } else {
        $error_message = "Invalid username or password. Please try again.";
    }
}

echo '<h1>Login</h1>
<form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    </br></br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    </br></br>
    <button type="submit">Log In</button>
</form>';

echo '<a href="registration.php">No Account? Register</br>';

print"
<style>

body {
    background-image: url('assets/vergil-grin.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    font-family: Arial, sans-serif;
    text-align: center;
}

header {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 20px;
}

nav {
    background-color: #444;
    color: #fff;
    padding: 10px;
}

nav ul {
    list-style-type: none;
    padding: 0;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

main {
    padding: 20px;
}

footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 10px;
}

</style>
";

