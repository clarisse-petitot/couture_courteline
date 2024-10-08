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

$utilisateur = $token->getUtilisateur();

if (isset($_GET["id_creation"])) {
    $id_page = 2;
    deleteCreation($_GET["id_creation"]);
}
//Page fin
else {
    $id_page = 1;
    $requete = 'Nom du Patron';
    $url = "/admin/administration.php?token=" . $_GET['token'];
}
$popup = true;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprime Patron</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "../components/navbar.php";
        require "../components/bouton-admin.php";
        $page = 'form-supprime-patron';
        $titre = 'Supprimer un patron';
        ?>
        <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10 z-20">
            <?php
            if ($id_page == 1) {
                $bouton = 'Valider';
                require "../components/form-patron.php";
            }
            if($id_page == 2){
                $message = "Validation";
                $desc = "Le patron a bien été supprimé";
                $retour = "/admin/administration.php?token=".$_GET['token'];
                require "../components/finish.php";
            }
            ?>
        </div>
    </div>

</body>

</html>