<?php
session_start();
include 'includes/_config.php';
include 'includes/_database.php';
include 'includes/_functions.php';

if (!isset($_SESSION['user'])) {
    addError('not_logged_in');
    redirectTo('login.php');
}

$user = $_SESSION['user'];

// Récupère le chemin de l'image
$query = $dbCo->prepare("SELECT image_path FROM img WHERE id_img = :id_img");
$query->execute(['id_img' => $user['id_img']]);
$imagePath = $query->fetchColumn();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($user['name']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p><img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Image de profil" width="150"></p>
    <a href="logout.php">Déconnexion</a>
</body>
</html>