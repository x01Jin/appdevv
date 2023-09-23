<?php

print"
Task Management System</br>
<hr></hr>
</br>";

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
    background-image: url('https://media.tenor.com/f3wDhPtHfEoAAAAC/vergil-grin.gif');
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

