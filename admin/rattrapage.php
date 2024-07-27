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
    $requete = "Personne qui rattrape";
    $url = "/admin/rattrapage.php?token=" . $_GET['token'];
} else {
    if (!isset($_GET["id_horaire"]) && isset($_GET["id_utilisateur"]) && !isset($_GET["id_cours"])) {
        $id_page = 3;
        $requete = "Cours du rattrapage";
        $eleve = getUtilisateurFromId($_GET["id_utilisateur"]);
        $url = "/admin/rattrapage.php?token=" . $_GET['token'] . "&id_horaire=" . $eleve->getHoraire()->getIdHoraire();
        if ($eleve->getNbrRattrapage() == 0) {
            header("Location: ../pas-rattrapage.php");
            exit;
            // Redirection page pas de rattrapage;
        }
    } else {
        if (isset($_GET["id_horaire"]) && isset($_GET["id_utilisateur"]) && !isset($_GET["id_cours"])) {
            $id_page = 4;
            $allcours = getAllRattrapagesFromIdUtilisateurIdHoraire($_GET['id_utilisateur'], $_GET["id_horaire"]);
            $requete = "Date du rattrapage";
            $url = "/admin/rattrapage.php?token=" . $_GET['token'] . "&id_utilisateur=" . $_GET["id_utilisateur"];
        } else {
            if (isset($_GET["id_utilisateur"]) && isset($_GET["id_cours"])) {
                $eleve = getUtilisateurFromId($_GET["id_utilisateur"]);
                $id_page = 5;
                if (!appartient($eleve->getIdUtilisateur(), $_GET["id_cours"])) {
                    if (isAbsent($eleve->getIdUtilisateur(), $_GET["id_cours"])) {
                        deleteAbsence($eleve->getIdUtilisateur(), $_GET["id_cours"]);
                    } else {
                        createRattrapage($eleve->getIdUtilisateur(), $_GET["id_cours"]);
                    }
                    changeNbrRattrapage($eleve->getIdUtilisateur(), $eleve->getNbrRattrapage() - 1);
                    $eleve->setRattrapage($eleve->getNbrRattrapage() - 1);
                }
            }
            //Page fin
            else {
                $id_page = 1;
                $requete = "Cours de la personne";
            }
        }
    }
}

$utilisateur = $token->getUtilisateur();

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rattrapage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "../components/navbar.php";
        $page = "rattrapage";
        $titre = "Choisir un rattrapage";
        if ($id_page == 1) {
            require "../components/form-horaire.php";
        }
        if ($id_page == 2) {
            $eleves = getUtilisateurFromIdHoraire($_GET['id_horaire']);
            require "../components/form-personne.php";
        }
        if ($id_page == 3) {
            require "../components/form-horaire.php";
        }
        if ($id_page == 4) {
            require "../components/form-cours.php";
        }
        require '../components/footer.php';
        ?>
    </div>

</body>

</html>