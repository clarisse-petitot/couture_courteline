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
    $requete = 'Personne absente';
    $url="/admin/absence.php?token=".$_GET['token'];
} else {
    if (isset($_GET["id_utilisateur"]) && !isset($_GET["id_cours"])) {
        $allcours = getAllCoursFromIdUtilisateur($_GET['id_utilisateur']);
        $eleve = getUtilisateurFromId($_GET["id_utilisateur"]);
        $id_page = 3;
        $requete = "Date de l'absence";
        $url="/admin/absence.php?token=".$_GET['token']."&id_horaire=".$eleve->getHoraire()->getIdHoraire();
    } else {
        if (isset($_GET["id_utilisateur"]) && isset($_GET["id_cours"])) {
            $eleve = getUtilisateurFromId($_GET["id_utilisateur"]);
            $id_page = 4;
            if (appartient($eleve->getIdUtilisateur(), $_GET["id_cours"])) {
                if (isRattrapage($eleve->getIdUtilisateur(), $_GET["id_cours"])) {
                    deleteRattrapage($eleve->getIdUtilisateur(), $_GET["id_cours"]);
                } else {
                    createAbsence($eleve->getIdUtilisateur(), $_GET["id_cours"]);
                }
                $cours = getCours($_GET["id_cours"]);
                if ($cours->getDate()->getTimestamp() - time() >= 86400) {
                    changeNbrRattrapage($eleve->getIdUtilisateur(), $eleve->getNbrRattrapage() + 1);
                    $eleve->setRattrapage($eleve->getNbrRattrapage() + 1);
                }
            }
        }
        //Page fin
        else {
            $id_page = 1;
            $requete = 'Cours de la personne';
        }
    }
}

$utilisateur = $token->getUtilisateur();
$page = "absence";
$titre = "Déclarer une absence";

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
        require '../components/footer.php';
        ?>
    </div>

</body>

</html>