<?php
session_start();

include 'includes/_config.php';
include 'includes/_database.php';
include 'includes/_functions.php';

generateToken();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <header>
        <H1>Formulaire de Création de compte</H1>
    </header>
    <main>
        <h2>Pour vous creer un compte ou s'authentifier :</h2>
        <ul>
            <li>
                <p>Creer un compte :</p>
                <a href="register.php">S'enregistrer</a>
            </li>
            <li>
                <p>S'authentifier :</p>
                <a href="login.php">Se connecter</a>
            </li>
            <li>
                <p>Gerer les comptes :</p>
                <a href="manage_users.php">Gerer les comptes</a>
            </li>
        </ul>
    </main>
</body>

</html>