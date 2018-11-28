<?php


// Connexion avec création d'une variable dbh (database handler (gestionnaire de base de données)) qui utilise les constantes définies dans le fichier config.php / La deuxième ligne attribue des caractéristiques pour gérer les éventuelles erreurs : s'il n'y a pas d'erreur, cette ligne n'est pas nécessaire
function connexion()
{
    $dbh = new PDO(DB_DSN,DB_USER,DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}


?>