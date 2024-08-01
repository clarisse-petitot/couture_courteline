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

if (isset($_GET["role"])) {
    $id_page = 2;
    $url = "/admin/form-ajout-utilisateur.php?token=" . $_GET['token'];
} else {
    $id_page = 1;
    $requete = "Role de la personne";
    $url = "/admin/administration.php?token=" . $_GET['token'];
}

if (isset($_POST['submit'])) {
    if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['prenom']) && !empty($_POST['prenom'])) {
        $id_page=3;
        if(empty($_POST['id_horaire']))
        {
            $_POST['id_horaire']=1;
        }
        createUtilisateur($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['id_horaire'], $_GET['role']);
        //Page fin
    }
} else {
    $res = null;
    $_POST["email"] = null;
    $_POST["id_horaire"] = null;
    $_POST["nom"] = null;
    $_POST["prenom"] = null;
}
$popup = true;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout Utilisateur</title>
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
        $page = 'form-ajout-utilisateur';
        $titre = 'Ajouter un utilisateur';
        ?>
        <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10 z-20">
            <?php
            if ($id_page == 1) {
                $bouton = 'Suivant';
                require "../components/form-role.php";
            }
            if ($id_page == 2) {
                require "../components/form-utilisateur.php";
            }
            if($id_page == 3){
                $message = "Validation";
                $desc = "L'utilisateur a bien été enregistré";
                $retour = "/admin/administration.php?token=".$_GET['token'];
                require "../components/finish.php";
            }
            ?>
        </div>
    </div>

</body>

</html>