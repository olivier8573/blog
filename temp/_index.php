<?php

// Le fichier config.php contient les identifiants de connexion sous forme de variables.
include('config/config.php');

// Le fichier bdd.lib.php contient la fonction connexion qui permet de générer un nouveau PDO (PHP Data Object)
include('lib/bdd.lib.php');


$resultats = [];

// Tentative d'exécution 'try' qui sous-entend qu'une alternative 'catch' peut être précisée (afin notamment d'éviter d'afficher le mot de passe en clair en cas d'échec) / La flèche correspond à l'équivalent du "." de Javascript suivie d'une fonction qui est propre à l'objet PDO
try {

    // Etape 1 : connexion à la base de données à partir de la fonction connexion du fichier bdd.lib.php
    connexion();

    // Etape 2 : préparation de la requête
    $sth = $dbh->prepare('SELECT orderNumber, orderDate, requiredDate, status FROM orders');

    // Etape 3 : exécution de la requête (pas de paramètres supplémentaires à transmettre dans les parenthèses)
    $sth->execute();

    // Etape 4 : stockage des résultats dans la variable $results (l'attribut fetchAll permet de préciser que l'on veut le résultat sous la forme d'un tableau ASSOCIATIF, on aurait pu mettre FETCH_NUM pour avoir un tableau avec des index classés par numéros)
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);

}

// Alternative en cas d'échec au 'try'
catch(PDOException $e) {

    $error = 'Une erreur de connexion a eu lieu'.$e->getMessage();
}

include('tpl/index.phtml');