<?php
session_start();
include 'includes/_config.php';
include 'includes/_database.php';
include 'includes/_functions.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php echo getHtmlErrors($errors); ?>
    <?php echo getHtmlMessages($messages); ?>
    <form action="actions.php" method="post">
        <label for="name">Nom:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>