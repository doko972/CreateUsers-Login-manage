<?php
session_start();
include 'includes/_config.php';
include 'includes/_database.php';
include 'includes/_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    // $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $dbCo->prepare("SELECT * FROM users WHERE name = :name");
    $query->execute(['name' => $name]);
    $user = $query->fetch();

    // $query = $dbCo->prepare("SELECT * FROM users WHERE email = :email");
    // $query->execute(['email' => $email]);
    // $user = $query->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        addMessage('login_success');
        redirectTo('dashboard.php');
    } else {
        addError('login_fail');
        redirectTo('login.php');
    }
}

?>