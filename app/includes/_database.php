<?php
$_HOST = 'db'; //ip adress
$_DBNAME = 'matrice'; //database name
$_LOGIN = 'matrice'; //database login
$_PASSWD = 'matrice'; //database password

try {
    $dbCo = new PDO(
        'mysql:host=' . $_HOST . ';
        dbname=' . $_DBNAME . ';
        charset=utf8',
        $_LOGIN,
        $_PASSWD
    );
    $dbCo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('ERREUR CONNEXION MYSQL: ' 
    . $e->getMessage());
}