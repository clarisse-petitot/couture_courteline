<?php

require_once __DIR__ . "/../classes.php";
require_once __DIR__ . "/../fonctions.php";

if (!isset($_GET["token"])) {
    http_response_code(403);
    header("Location: ../connexion.php");
    exit;
};

$token = getToken($_GET["token"]);

if (is_null($token)) {
    http_response_code(403);
    header("Location: ../connexion.php");
    exit;
}

if (!$token->isValide()) {
    http_response_code(403);
    header("Location: ../connexion.php");
    exit;
}

if (isset($_GET["id_horaire"]) && !isset($_GET["id_utilisateur"]) && !isset($_GET["id_cours"])) {
    $id_page = 2;
} else {
    if (isset($_GET["id_horaire"]) && isset($_GET["id_utilisateur"]) && !isset($_GET["id_cours"])) {
        $id_page = 3;
    } else {
        if (isset($_GET["id_horaire"]) && isset($_GET["id_utilisateur"]) && isset($_GET["id_cours"])) {
            $id_page = 4;
            //Page fin + envoie formulaire
        } else {
            $id_page = 1;
        }
    }
}

$utilisateur = $token->getUtilisateur();
$page = "absence";

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "../components/navbar.php";
        if ($id_page == 1) {
            require "../components/form-horaire.php";
        }
        if ($id_page == 2) {
            require "../components/form-personne.php";
        }
        if ($id_page == 3) {
            require "../components/form-cours.php";
        }
        if ($id_page == 4) {
            require "../components/form-finish.php";
        }
        require '../components/footer.php';
        ?>
    </div>

</body>

</html>