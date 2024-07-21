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

// Gestion des histoires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_story' && isset($_POST['title']) && isset($_POST['content'])) {
            $query = $dbCo->prepare("INSERT INTO stories (id_user, title, content, created_at) VALUES (:id_user, :title, :content, NOW())");
            $query->execute([
                'id_user' => $user['id_users'],
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ]);
            $_SESSION['story_added'] = true;
            addMessage('story_added');
        } elseif ($_POST['action'] === 'update_story' && isset($_POST['story_id']) && isset($_POST['title']) && isset($_POST['content'])) {
            $query = $dbCo->prepare("UPDATE stories SET title = :title, content = :content, updated_at = NOW() WHERE id_story = :story_id AND id_user = :id_user");
            $query->execute([
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'story_id' => $_POST['story_id'],
                'id_user' => $user['id_users']
            ]);
            addMessage('story_updated');
        } elseif ($_POST['action'] === 'delete_story' && isset($_POST['story_id'])) {
            $query = $dbCo->prepare("DELETE FROM stories WHERE id_story = :story_id AND id_user = :id_user");
            $query->execute([
                'story_id' => $_POST['story_id'],
                'id_user' => $user['id_users']
            ]);
            addMessage('story_deleted');
        }

        // Redirection après traitement du formulaire pour éviter la resoumission
        redirectTo('dashboard.php');
    }
}

// Gestion de l'image de profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
        $query = $dbCo->prepare("UPDATE img SET image_path = :image_path WHERE id_img = :id_img");
        $query->execute(['image_path' => $uploadFile, 'id_img' => $user['id_img']]);
        $_SESSION['user']['image_path'] = $uploadFile;
        addMessage('profile_image_updated');
        redirectTo('dashboard.php');
    } else {
        addError('upload_fail');
        redirectTo('dashboard.php');
    }
}

// Récupère les histoires de l'utilisateur
$query = $dbCo->prepare("SELECT * FROM stories WHERE id_user = :id_user");
$query->execute(['id_user' => $user['id_users']]);
$stories = $query->fetchAll();

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

    <h2>Changer l'image de profil</h2>
    <form action="dashboard.php" method="post" enctype="multipart/form-data">
        <label for="profile_image">Nouvelle image de profil:</label>
        <input type="file" name="profile_image" id="profile_image">
        <button type="submit">Changer l'image</button>
    </form>

    <?php if (!isset($_SESSION['story_added'])): ?>
        <h2>Ajouter une histoire</h2>
        <form action="dashboard.php" method="post">
            <input type="hidden" name="action" value="add_story">
            <label for="title">Titre:</label>
            <input type="text" name="title" id="title" required>
            <br>
            <label for="content">Contenu:</label>
            <textarea name="content" id="content" required></textarea>
            <br>
            <button type="submit">Ajouter</button>
        </form>
    <?php else: ?>
        <p>Votre histoire a été ajoutée avec succès.</p>
        <?php unset($_SESSION['story_added']); ?>
    <?php endif; ?>

    <h2>Vos histoires</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stories as $story) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($story['title']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($story['content'])); ?></td>
                    <td>
                        <?php if (!isset($_SESSION['story_added'])): ?>
                            <form action="dashboard.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="action" value="update_story">
                                <input type="hidden" name="story_id" value="<?php echo $story['id_story']; ?>">
                                <label for="title_<?php echo $story['id_story']; ?>">Titre:</label>
                                <input type="text" name="title" id="title_<?php echo $story['id_story']; ?>" value="<?php echo htmlspecialchars($story['title']); ?>" required>
                                <br>
                                <label for="content_<?php echo $story['id_story']; ?>">Contenu:</label>
                                <textarea name="content" id="content_<?php echo $story['id_story']; ?>" required><?php echo htmlspecialchars($story['content']); ?></textarea>
                                <br>
                                <button type="submit">Mettre à jour</button>
                            </form>
                            <form action="dashboard.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="action" value="delete_story">
                                <input type="hidden" name="story_id" value="<?php echo $story['id_story']; ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="logout.php">Déconnexion</a>
</body>
</html>