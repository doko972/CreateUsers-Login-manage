<?php
try {
    $dbCo = new PDO(
        'mysql:host=db;
        dbname=matrice;
        charset=utf8',
        'matrice',
        'matrice'
    );
    $dbCo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('ERREUR CONNEXION MYSQL: ' 
    . $e->getMessage());
}