<?php

require_once "classes.php";
require_once "fonctions.php";

if(!isset($GET["token"])) {
    header("Location: connexion.php");
    exit;
};

$token = getToken($GET["token"]);
$utilisateur = $token->getUtilisateur();
$allcours = getAllCours($utilisateur->getIdUtilisateur());