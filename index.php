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
                <p>
                    Seuls les élèves inscrits aux cours ont accès à ce site, vous pouvez voir tous vos cours et prevenir de vos absences.
                    <br>
                    <br>
                    Si vous prévenez de votre absence au moins 24h avant le début de votre cours pour pourrez le rattraper et choisir votre rattrapage à l'horaire que vous souhaitez suivant les disponibilités.
                    <br>
                    <br>
                    Vous pouvez aussi voir les différents patrons proposés par Courteline, télécharger les patrons qui vous interessent, voir les creations des autres élèves correpondant aux patrons
                    et vous-même pourrez déposer votre photo pour que les autres élèves puissent voir votre création à leur tour.
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