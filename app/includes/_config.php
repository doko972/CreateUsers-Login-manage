<?php

$globalUrl = 'http://localhost:8080';

$errors = [
    'csrf' => 'Votre session est invalide.',
    'referer' => 'D\'où venez vous ?',
    'insert_ko' => 'Erreur lors de la sauvegarde de l\'utilisateur.',
    'update_ko' => 'Erreur lors de la modification de l\'utilisateur.',
    'delete_ko' => 'Erreur lors de la suppression de l\'utilisateur.',
    'no_action' => 'Aucune action identifiée.',
    'upload_fail' => 'Erreur lors de l\'upload de l\'image.',
    'login_fail' => 'Échec de la connexion, vérifiez votre email et votre mot de passe.',
    'access_denied' => 'Accès refusé.'
];

$messages = [
    'insert_ok' => 'Utilisateur sauvegardé avec succès.',
    'update_ok' => 'Utilisateur modifié avec succès.',
    'login_success' => 'Connexion réussie.',
    'logout_success' => 'Déconnexion réussie.',
    'role_update_success' => 'Rôle de l\'utilisateur mis à jour avec succès.'
];
?>