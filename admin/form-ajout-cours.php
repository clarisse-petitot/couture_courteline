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

function getDateTime($date, $horaire)
{
    $res = substr($date, 6, 4) . '-' . substr($date, 0, 2) . '-' . substr($date, 3, 2) . ' ';
    $heure = $horaire->getHeure();
    if ($heure[1]== "h") {
        $res = $res . substr($heure, 0, 1) . ':' . substr($heure, 2, 2);
    } else {
        $res = $res . substr($heure, 0, 2) . ':' . substr($heure, 3, 2);
    }
    $res = $res . ':00';
    return $res;
}

if (isset($_GET["id_horaire"]) && !isset($_GET["date"])) {
    $id_page = 2;
    $requete = 'Date du cours';
    $url = "/admin/form-ajout-cours.php?token=" . $_GET['token'];
    $allcours = getCoursFromIdHoraire($_GET["id_horaire"]);
} else {
    if (isset($_GET["date"]) && isset($_GET['id_horaire'])) {
        $id_page = 3;
        $horaire = getHoraireFromId($_GET["id_horaire"]);
        $date = getDateTime($_GET["date"], $horaire);
        createCours($date, $_GET["id_horaire"]);
    }
    //Page fin
    else {
        $id_page = 1;
        $requete = 'Horaire du cours';
        $url = "/admin/administration.php?token=" . $_GET['token'];
    }
}

$popup = true;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "../components/navbar.php";
        require "../components/bouton-admin.php";
        $page = 'form-ajout-cours';
        $titre = 'Ajouter un cours';
        ?>
        <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10 z-20">
            <?php
            if ($id_page == 1) {
                $bouton = 'Suivant';
                require "../components/form-horaire.php";
            }
            if ($id_page == 2) {
                $bouton = 'Valider';
                require "../components/form-date.php";
            }
            ?>
        </div>
    </div>

</body>

</html>