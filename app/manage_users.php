<?php
session_start();
include 'includes/_config.php';
include 'includes/_database.php';
include 'includes/_functions.php';

// Vérifie si l'utilisateur connecté est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    addError('access_denied');
    redirectTo('login.php');
}

// Mise à jour du rôle de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['role'])) {
    $userId = intval($_POST['user_id']);
    $role = $_POST['role'] === 'admin' ? 'admin' : 'user';

    $query = $dbCo->prepare("UPDATE users SET role = :role WHERE id_users = :user_id");
    $query->execute(['role' => $role, 'user_id' => $userId]);

    addMessage('role_update_success');
    redirectTo('manage_users.php');
}

// Récupère la liste des utilisateurs
$query = $dbCo->prepare("SELECT users.id_users, users.name, users.email, users.role, img.image_path 
FROM users JOIN img ON users.id_img = img.id_img");
$query->execute();
$users = $query->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des inscrits</title>
</head>
<body>
    <h1>Gestion des inscrits</h1>
    <?php echo getHtmlErrors($errors); ?>
    <?php echo getHtmlMessages($messages); ?>

    <table border="1">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($user['image_path']); ?>" alt="Photo de profil" width="50"></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <form action="manage_users.php" method="post" style="display:inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id_users']; ?>">
                            <select name="role">
                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Utilisateur</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                            </select>
                            <button type="submit">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>