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
            $query = $dbCo->prepare("INSERT INTO stories (id_user, title, content, created_at) 
            VALUES (:id_user, :title, :content, NOW())");
            $query->execute([
                'id_user' => $user['id_users'],
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ]);
        } elseif ($_POST['action'] === 'update_story' && isset($_POST['story_id']) && isset($_POST['title']) && isset($_POST['content'])) {
            $query = $dbCo->prepare("UPDATE stories SET title = :title, content = :content, updated_at = NOW()
             WHERE id_story = :story_id AND id_user = :id_user");
            $query->execute([
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'story_id' => $_POST['story_id'],
                'id_user' => $user['id_users']
            ]);
        } elseif ($_POST['action'] === 'delete_story' && isset($_POST['story_id'])) {
            $query = $dbCo->prepare("DELETE FROM stories 
            WHERE id_story = :story_id AND id_user = :id_user");
            $query->execute([
                'story_id' => $_POST['story_id'],
                'id_user' => $user['id_users']
            ]);
        }
    }
}

// Image de profil
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
    } else {
        addError('upload_fail');
    }
}

// Histoires de l'utilisateur
$query = $dbCo->prepare("SELECT * FROM stories WHERE id_user = :id_user");
$query->execute(['id_user' => $user['id_users']]);
$stories = $query->fetchAll();

// Chemin de l'image
$query = $dbCo->prepare("SELECT image_path FROM img WHERE id_img = :id_img");
$query->execute(['id_img' => $user['id_img']]);
$imagePath = $query->fetchColumn();
?>