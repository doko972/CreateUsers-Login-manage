<?php
session_start();
include 'includes/_config.php';
include 'includes/_database.php';
include 'includes/_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hachage du mot de passe
    $createTime = date('Y-m-d H:i:s');
    $role = 'user'; // Par défaut, rôle utilisateur
    $uploadDir = 'uploads/';
    $imagePath = '';

    // Vérifie que le répertoire d'upload existe
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Gestion de l'upload de l'image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
            $imagePath = $uploadFile;
        } else {
            $imagePath = $uploadDir . 'default.png'; // Chemin par défaut si l'upload échoue
            addError('upload_fail');
        }
    } else {
        $imagePath = $uploadDir . 'default.png'; // Chemin par défaut si aucune image n'est uploadée
    }

    // Insère l'image dans la table img et obtient l'id
    $query = $dbCo->prepare("INSERT INTO img (image_path) VALUES (:image_path)");
    $query->execute(['image_path' => $imagePath]);
    $idImg = $dbCo->lastInsertId();

    // Insère le nouvel utilisateur
    $query = $dbCo->prepare("INSERT INTO users (name, email, password, create_time, id_img, role) 
    VALUES (:name, :email, :password, :create_time, :id_img, :role)");
    $isInsertOk = $query->execute([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'create_time' => $createTime,
        'id_img' => $idImg,
        'role' => $role
    ]);

    if ($isInsertOk) {
        addMessage('insert_ok');
        redirectTo('login.php');
    } else {
        addError('insert_ko');
        redirectTo('register.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <?php echo getHtmlErrors($errors); ?>
    <?php echo getHtmlMessages($messages); ?>
    <form action="register.php" method="post" enctype="multipart/form-data">
        <label for="name">Nom:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="profile_image">Image de profil:</label>
        <input type="file" name="profile_image" id="profile_image">
        <br>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>