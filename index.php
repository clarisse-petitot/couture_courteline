<?php

require_once "classes.php";
require_once "fonctions.php";

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

if($utilisateur->getRole()=='admin'){
    http_response_code(403);
    header("Location: admin/accueil.php?token=".$token->getToken());
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
    <div class="min-h-screen">
    <?php
    require "components/navbar.php";
    ?>
    <h1 class=" p-10 lg:text-4xl text-3xl lg:leading-9 leading-7 text-gray-800 font-semibold">Bienvenue sur le site des cours de Couture de Catherine</h1>
    </div>
    <?php
    require './components/footer.php';
    ?>

</body>

</html>