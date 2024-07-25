<?php

require "classes.php";
require "fonctions.php";

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

$creations = getCreations();
$categories = getCategories();
$filtres = getFiltres();

if (count($_GET) > 1) {
    foreach ($creations as $key => $creation) {
        if (count($filtres) != 0) {
            $categorie_in = false;
            foreach ($creation->getCategories() as $categorie) {
                if (in_array($categorie, $filtres)) {
                    $categorie_in = true;
                }
            }
            if (!$categorie_in) {
                unset($creations[$key]);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creations | La Couture de CP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://unpkg.com/alpinejs" defer=""></script>
    <style>
        body {
            background: url(./img/blob.jpg);
            background-size: cover;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require './components/navbar.php';
        require './components/filters-patrons.php';
        require './components/cards.php';
        require './components/footer.php';
        ?>
    </div>

</body>

</html>