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

$allcours = getAllCours();
$bouton = "Voir l'appel";
$popup = false;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prochains Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "../components/navbar.php";
        $message = 'Validation';
        if(isset($_GET['page']) && $_GET['page']=='patron'){
            $desc = "Le patron a bien été enregistrée";
        }
        else{
            if(isset($_GET['page']) && $_GET['page']=='annee'){
                $desc = "Les données ont bien été enregistrée";
            }
            else{
                $desc="";
            }
        }
        $retour = "/admin/administration.php?token=".$_GET['token'];
        require "../components/finish.php";
        require '../components/footer.php';
        ?>
    </div>

</body>

</html>