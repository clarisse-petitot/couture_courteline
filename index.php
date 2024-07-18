<?php

require_once "classes.php";
require_once "fonctions.php";

if (!isset($_GET["token"])) {
    header("Location: connexion.php");
    exit;
};

$token = getToken($_GET["token"]);
$utilisateur = $token->getUtilisateur();

if (isset($_GET["id_absence"]) and appartient($utilisateur->getIdUtilisateur(), $_GET["id_absence"]))
{
    deleteAppel($utilisateur->getIdUtilisateur(),$_GET["id_absence"]);
    addRattrapage($utilisateur->getIdUtilisateur(), $utilisateur->getRattrapage());
}

$allcours = getAllCours($utilisateur->getIdUtilisateur());

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <?php
    require "components/navbar.php";
    require "components/cours.php";
    ?>

</body>

</html>