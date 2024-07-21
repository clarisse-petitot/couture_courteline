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

if (isset($_GET["id_cours"]) && appartient($utilisateur->getIdUtilisateur(), $_GET["id_cours"])) {
    deleteAppel($utilisateur->getIdUtilisateur(), $_GET["id_cours"]);
    $cours = getCours($_GET["id_cours"]);
    if ($cours->getDate()->getTimestamp() - time() >= 86400) {
        changeRattrapage($utilisateur->getIdUtilisateur(), $utilisateur->getRattrapage()+1);
        $utilisateur->setRattrapage($utilisateur->getRattrapage()+1);
    }
}

$allcours = getAllCours($utilisateur->getIdUtilisateur());
$bouton = "Prévenir mon abscence";
$page = "absences";

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prochains Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <?php
    require "components/navbar.php";
    require "components/cours.php";
    ?>

</body>

</html>