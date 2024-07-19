<?php

require_once "classes.php";
require_once "fonctions.php";

if (!isset($_GET["token"])) {
    http_response_code(403);
    header("Location: connexion.php");
    exit;
};

$token = getToken($_GET["token"]);
$utilisateur = $token->getUtilisateur();

if (isset($_GET["id_rattrapage"]) && !appartient($utilisateur->getIdUtilisateur(), $_GET["id_rattrapage"])) {
    createAppel($utilisateur->getIdUtilisateur(), $_GET["id_rattrapage"], $utilisateur->getRattrapage());
    $utilisateur->setRattrapage($utilisateur->getRattrapage()-1);
}

$allrattrapages = getAllRattrapages($utilisateur->getIdUtilisateur());

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rattrapages</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <?php
    require "components/navbar.php";
    if ($utilisateur->getRattrapage() > 0) {
        require "components/cours_rattrapages.php";
    }
    ?>

</body>

</html>