<?php
include_once "db.php";

print "
Task Management System</br>
<hr></hr>
</br>";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $role = $_POST["role"];

    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->bindParam(3, $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(4, $role, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: appdev.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again later.";
        }
    }
}

echo '<h1>Registration</h1>';

if (!empty($errors)) {
    echo '<div style="color: red;">';
    foreach ($errors as $error) {
        echo $error . '<br>';
    }
    echo '</div>';
}

echo '<form action="registration.php" method="POST">
    <label for="username" style="color: white;">Username:</label>
    <input type="text" id="username" name="username" required>
    </br></br>
    <label for="email" style="color: white;">Email:</label>
    <input type="email" id="email" name="email" required>
    </br></br>
    <label for="password" style="color: white;">Password:</label>
    <input type="password" id="password" name="password" required>
    </br></br>
    <label for="confirm_password" style="color: white;">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    </br></br>
    <label for="role" style="color: white;">Role:</label>
    <select id="role" name="role" required>
        <option value="employee">Employee</option>
        <option value="admin">Admin</option>
    </select>
    </br></br>
    <button type="submit">Register</button>
</form>
<a href="appdev.php">Have Account? Log in</a>';

print "
<style>
body {
    background-image: url('assets/vergil.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    font-family: Arial, sans-serif;
    text-align: center;
}
</style>
";
