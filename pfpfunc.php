<?php

$userID = $_SESSION["user_id"];

$sql = "SELECT profile_picture FROM users WHERE id_number = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$profilePicture = $user['profile_picture'];
