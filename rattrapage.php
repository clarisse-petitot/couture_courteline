<?php

require_once "classes.php";
require_once "fonctions.php";

if (!isset($_GET["token"])) {
    header("Location: connexion.php");
    exit;
};

$token = getToken($_GET["token"]);
$utilisateur = $token->getUtilisateur();
$allrattrapages = getAllRattrapages($utilisateur->getIdUtilisateur());
$cours = getCours($_GET["id_rattrapage"]);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rattrapage</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <?php
    require "components/navbar.php";
    require "components/cours_rattrapages.php";
    ?>

    <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10">
        <div class="max-h-full w-full max-w-xl rounded-2xl bg-white">
            <div class="w-full">
                <div class="m-20">
                    <div class="mb-8">
                        <h1 class="mb-4 text-3xl font-extrabold">Êtes-vous sûr de vouloir choisir ce rattrapage ?</h1>
                        <p class="text-gray-600"><?= $cours->getHoraire()->getJour() ?> <?= getTraduction($cours->getDate()->format("j F")) ?> à <?= $cours->getHoraire()->getHeure() ?></p>
                    </div>
                    <div class="space-y-4">
                        <div class="p-1"><a href="rattrapages.php?token=<?= $_GET["token"] ?>&id_rattrapage=<?= $_GET["id_rattrapage"] ?>"><button class=" p-3 bg-sky-700 rounded-full text-white w-full font-semibold">Valider mon rattrapage</button></a></div>
                        <div class="p-1"><a href="rattrapages.php?token=<?= $_GET["token"] ?>"><button class="p-3 bg-white border border-sky-700 rounded-full w-full font-semibold">Annuler</button></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>