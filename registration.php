<?php

print"
Task Management System</br>
<hr></hr>
</br>";

echo '<h1>Registration</h1>
<form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    </br></br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    </br></br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    </br></br>
    <button type="submit">Register</button>
</form>
<a href="appdev.php">Have Account? Log in</a>';

print"
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
