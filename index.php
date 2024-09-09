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

if ($utilisateur->getRole() == 'admin') {
    http_response_code(403);
    header("Location: admin/accueil.php?token=" . $token->getToken());
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "components/navbar.php";
        ?>
        <div class="flex flex-col p-10 md:flex-row gap-10 w-full justify-center items-center">
            <div class="flex flex-col gap-12 px-5 md:w-[700px]">
                <h1 class="font-black text-4xl text-purple-900">Bienvenue sur le site des cours de Couture de Courteline</h1>
                <p>Seuls les adhérent.es inscrit.es ont accès à cette plateforme, qui vous permet de :
                    <br>
                    <br>
                    1/ planifier tous vos cours et prévenir de vos absences
                    <br>
                    2/ retrouver tous les patrons proposés par Catherine lors des cours en téléchargement.
                    <br>
                    <br>
                    Fonctionnement et règles :
                    <br>
<br>
                    1/ absences et rattrapages :
                    <br>
                    Cette possibilité vous est offerte car nous proposons de nombreux cours de couture. C'est une opportunité que nous souhaitons maintenir si un cadre collectif et consciencieux est respecté par tout.es.
                   <br>
                    • prévenez de votre absence au moins 24h avant le début de votre cours pour pouvoir le rattraper
                    <br>
                    • choisissez votre créneau de rattrapage à l'horaire que vous souhaitez suivant les disponibilités. (pas de rattrapages en sept/oct, c'est la rentrée et il faut laisser le temps à chaque groupe de trouver son rythme. Mais vous pourrez rattraper vos absences déclarées plus tard)
<br>
<br>
                    2/ côté créations :
                    <br>
                    • téléchargez les patrons qui vous intéressent
                    <br>
                    • postez vos réalisations textiles pour partager
                    <br>
                    • découvrez les créations des autres adhérents
                </p>
            </div>
            <div>
                <img src="/logo_courteline.png" class="h-32 px-5" alt="logo cp">
            </div>
        </div>
        <?php
        require './components/footer.php';
        ?>
    </div>

</body>

</html>