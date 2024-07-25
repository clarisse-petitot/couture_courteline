<?php

require_once "classes.php";
require_once "fonctions.php";

if (!isset($_GET["token"])) {
    http_response_code(403);
    header("Location: connexion.php");
    exit;
};

$token = getToken($_GET["token"]);

if (is_null($token)) {
    http_response_code(403);
    header("Location: connexion.php");
    exit;
}

if (!$token->isValide()) {
    http_response_code(403);
    header("Location: connexion.php");
    exit;
}

$utilisateur = $token->getUtilisateur();

if($utilisateur->getRole()=='admin'){
    http_response_code(403);
    header("Location: admin/accueil.php?token=".$token->getToken());
    exit;
}

if (isset($_GET["id_cours"]) && !appartient($utilisateur->getIdUtilisateur(), $_GET["id_cours"])) {
    if (isAbsent($utilisateur->getIdUtilisateur(), $_GET["id_cours"])) {
        deleteAbsence($utilisateur->getIdUtilisateur(), $_GET["id_cours"]);
    } else {
        createRattrapage($utilisateur->getIdUtilisateur(), $_GET["id_cours"]);
    }
    changeNbrRattrapage($utilisateur->getIdUtilisateur(), $utilisateur->getNbrRattrapage() - 1);
    $utilisateur->setRattrapage($utilisateur->getNbrRattrapage() - 1);
}

$allcours = getAllRattrapagesFromIdUtilisateur($utilisateur->getIdUtilisateur());
if ($allcours != []) {
    $i = 0;
    $exit = false;
    while ($exit && ($allcours[$i]->getDate()->format('n') == 9 || $allcours[$i]->getDate()->format('n') == 10)) {
        if (!isAbsent($utilisateur->getIdUtilisateur(), $allcours[$i]->getIdCours())) {
            unset($allcours[$i]);
        }
        if (!is_null($allcours[$i + 1])) {
            $i++;
        } else {
            $exit = true;
        }
    }
}

$bouton = "Choisir ce rattrapage";
$page = "rattrapages";

$heures = getHeures();
$jours = getJours();
$filtres = getFiltresRattrapages();
if (count($_GET) > 1) {
    foreach ($allcours as $key => $rattrapage) {
        if (count($filtres["jours"]) != 0 and !in_array($rattrapage->getJour(), $filtres["jours"])) {
            unset($allcours[$key]);
        }
        if (count($filtres["heures"]) != 0 and !in_array($rattrapage->getHeure(), $filtres["heures"])) {
            unset($allcours[$key]);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rattrapages</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://unpkg.com/alpinejs" defer=""></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "components/navbar.php";
        if ($utilisateur->getNbrRattrapage() > 0) {
            require "components/filters-rattrapage.php";
            require "components/cours.php";
        }
        require './components/footer.php';
        ?>
    </div>

</body>

</html>