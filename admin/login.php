<?php

include('../config/config.php');
include('../lib/bdd.lib.php');
$vue = 'login.phtml';

if (isset($_POST['aut_email'])) {


try {
     
    $login = $_POST['aut_email'];

    // Etape 1 : connexion à la base de données à partir de la fonction connexion du fichier bdd.lib.php
    $dbh = connexion();
                
    // Etape 2 : préparation de la requête sachant que le NULL correspond à la colonne aut_id qui nécessite une auto-incrémentation (obligatoire) / Tous les champs qui ont une spécificité "not null" doivent impérativement être pris en compte dans la requête (équivalent de champs obligatoires) / C'est à l'étape suivante que l'on indiquera qu'il faut utiliser le tableau $resultats : utilisation des index de ce tableau en les précédant de ":"
    $sth = $dbh->prepare('SELECT aut_email FROM auteur WHERE aut_email LIKE "$login"');
                
    // Etape 3 : exécution de la requête (pas de paramètres supplémentaires à transmettre dans les parenthèses)
    $sth->execute();

    // Etape 4 : stockage des résultats dans la variable $results (l'attribut fetchAll permet de préciser que l'on veut le résultat sous la forme d'un tableau ASSOCIATIF, on aurait pu mettre FETCH_NUM pour avoir un tableau avec des index classés par numéros)
    $resultats = $sth->fetch(PDO::FETCH_ASSOC);

}

// Alternative en cas d'échec au 'try'
catch(PDOException $e) {
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $messageErreur =  'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

}


include('tpl/layout.phtml');