<?php

require_once "../classes.php";
require_once "../fonctions.php";

if (!isset($_GET["token"])) {
    http_response_code(403);
    header("Location: connexion.php");
    exit;
};

$token = getToken($_GET["token"]);

if(is_null($token)){
    http_response_code(403);
    header("Location: connexion.php");
    exit;
}

if(!$token->isValide()){
    http_response_code(403);
    header("Location: connexion.php");
    exit;
}

$utilisateur = $token->getUtilisateur();
$cours_valide = getCours($_GET["id_cours"]);
$date_fin= clone $cours_valide->getDate();
$date_fin->add(new DateInterval('PT2H30M'));

if ($_GET["page"] == "absences") {
    $bouton = "Prévenir mon abscence";
    $question = "Êtes-vous sûr de cette absence ?";
    $allcours = getAllCours($utilisateur->getIdUtilisateur());
    $validation = "Valider mon absence";
}
else{
    $bouton = "Choisir ce rattrapage";
    $question = "Êtes-vous sûr de vouloir choisir ce rattrapage ?";
    $allcours = getAllRattrapages($utilisateur->getIdUtilisateur());
    $validation = "Valider mon rattrapage";
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <?php
    require "navbar.php";
    if($_GET["page"] == "rattrapages"){
        require "filters-rattrapage.php";
    }
    require "cours.php";
    ?>

    <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10">
        <div class="max-h-full w-full max-w-xl rounded-2xl bg-white">
            <div class="w-full">
                <div class="m-20">
                    <div class="mb-8">
                        <h1 class="mb-4 text-3xl font-extrabold"><?= $question ?></h1>
                        <p class="text-gray-600"><?= $cours_valide->getHoraire()->getJour() ?> <?=$date_fin->format("d")?> <?= getTraduction($cours->getDate()) ?> de <?=$cours->getDate()->format("G\hi")?> à <?=$date_fin->format("G\hi")?></p>
                    </div>
                    <div class="space-y-4">
                        <div class="p-1"><a href="/<?= $_GET["page"] ?>.php?token=<?= $_GET["token"] ?>&id_cours=<?= $_GET["id_cours"] ?>"><button class=" p-3 bg-blue-700 rounded-full text-white w-full font-semibold hover:bg-blue-800"><?= $validation ?></button></a></div>
                        <div class="p-1"><a href="/<?= $_GET["page"] ?>.php?token=<?= $_GET["token"] ?>"><button class="p-3 bg-white border border-blue-600 rounded-full text-blue-700 w-full font-semibold hover:bg-gray-100">Annuler</button></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>