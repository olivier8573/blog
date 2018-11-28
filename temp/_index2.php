<?php
include('config/config.php');
include('lib/bdd.lib.php');


$vue='index.phtml';
$title = 'Dashboard';

$activeMenu = 'home';

/** On essaie de se connecter et de faire notre requête
 * Principe des exception en programmation
 * Je vous expliquerai tout ça mais vous pouvez déjà lire ceci :
 * https://www.pierre-giraud.com/php-mysql/cours-complet/php-exceptions.php
 * http://php.net/manual/fr/language.exceptions.php
 * 
 */

try
{
    /** 1 : connexion au serveur de BDD - SGBDR */
    $dbh = connexion();

    /** 2 : Prépare ma requête SQL - 10 dernière commandes */
    $sth = $dbh->prepare('SELECT * FROM orders ORDER BY orderDate DESC LIMIT 1,10');
    $sth->execute();
    $orders = $sth->fetchAll(PDO::FETCH_ASSOC);

    /** 2 : Prépare ma requête SQL - Nombre de commande */
    $sth = $dbh->prepare('SELECT count(orderNumber) nbOrders FROM orders');
    $sth->execute();
    $nbOrders = $sth->fetch(PDO::FETCH_ASSOC);

    /** 2 : Prépare ma requête SQL - Nombre de clients */
    $sth = $dbh->prepare('SELECT count(customerNumber) as nbCustomers FROM customers');
    $sth->execute();
    $nbCustomers = $sth->fetch(PDO::FETCH_ASSOC);

    /** 2 : Prépare ma requête SQL - 10 dernièr clients */
    $sth = $dbh->prepare('SELECT * FROM customers ORDER BY customerNumber DESC LIMIT 1,10');
    $sth->execute();
    $clients = $sth->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e)
{
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $messageErreur =  'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

/** On inclu la vue pour afficher les résultats */
include('tpl/layout.phtml');




