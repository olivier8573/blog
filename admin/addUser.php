<?php


// Le fichier config.php contient les identifiants de connexion à la base de données sous forme de variables
include('../config/config.php');

// Le fichier bdd.lib.php contient la fonction connexion qui permet de générer un nouveau PDO (PHP Data Object) et se connecter en utilisant les identifiants de config.php
include('../lib/bdd.lib.php');

// On inclut dans une variable la vue du fichier qui sera affiché si tout fonctionne
$vue = 'addUser.phtml';
$erreur = false;

// On prépare un tableau vide (cela permet d'alimenter les value du formulaire)
$resultats = [
    'aut_name' => '',
    'aut_firstname' => '',
    'aut_email' => '',
    'aut_password' => '',
    'aut_password2' => ''
];

// Tentative d'exécution 'try' qui sous-entend qu'une alternative 'catch' peut être précisée (afin notamment d'éviter d'afficher le mot de passe en clair en cas d'échec) / La flèche correspond à l'équivalent du "." de Javascript suivie d'une fonction qui est propre à l'objet PDO
try {

    // Vérification de la présence d'un nom rempli dans le formulaire
    if (array_key_exists('aut_name',$_POST)) {

        // Alimentation du tableau des résultats même s'il y a des erreurs dans les données saisies
        $resultats = [
            'aut_name' => $_POST['aut_name'],
            'aut_firstname' => $_POST['aut_firstname'],
            'aut_email' => $_POST['aut_email'],
            'aut_password' => $_POST['aut_password'],
            'aut_password2' => $_POST['aut_password2']
        ];

        // Vérification de l'email
        $email = $_POST['aut_email'];
        if (filter_var($email,FILTER_VALIDATE_EMAIL) == false) {
            $erreur = 'Email invalide';
        }
        else {

            // Vérification de la cohérence du mot de passe
            $password = $_POST['aut_password'];
            $password2 = $_POST['aut_password2'];
            if ($password != $password2) {
                $erreur = 'Mots de passe non identiques';
            }

            else {

                // Hashage du mot de passe
                $passwordHash = password_hash($password,PASSWORD_DEFAULT);

                // Modification du tableau des résultats en précisant le mot de passe haché
                $resultats = [
                    'aut_name' => $_POST['aut_name'],
                    'aut_firstname' => $_POST['aut_firstname'],
                    'aut_email' => $email,
                    'aut_password' => $password
                ];
                
                // Etape 1 : connexion à la base de données à partir de la fonction connexion du fichier bdd.lib.php
                $dbh = connexion();
                
                // Etape 2 : préparation de la requête sachant que le NULL correspond à la colonne aut_id qui nécessite une auto-incrémentation (obligatoire) / Tous les champs qui ont une spécificité "not null" doivent impérativement être pris en compte dans la requête (équivalent de champs obligatoires) / C'est à l'étape suivante que l'on indiquera qu'il faut utiliser le tableau $resultats : utilisation des index de ce tableau en les précédant de ":"
                $sth = $dbh->prepare('INSERT INTO auteur (aut_id,aut_name,aut_firstname,aut_email,aut_password) VALUES (NULL,:aut_name,:aut_firstname,:aut_email,:aut_password)');
                
                // Etape 3 : exécution de la requête (pas de paramètres supplémentaires à transmettre dans les parenthèses)
                $sth->execute($resultats);
                
                // L'étape 4 prévoit habituellement le stockage des résultats des requêtes dans une variable mais ce n'est pas nécessaire puisque l'on ne récupère pas de données de la requête SQL

            }
        }
    }
}
    
// Alternative en cas d'échec au 'try'
catch(PDOException $e) {
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $messageErreur =  'Une erreur de connexion a eu lieu :'.$e->getMessage();
}


include('tpl/layout.phtml');