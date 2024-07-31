<?php

require "classes.php";
require "fonctions.php";

if (!isset($_GET["id_creation"])) {
    header("Location: patrons.php");
    exit;
};

$id_creation = $_GET["id_creation"];
$creation = getCreation($id_creation);

if (is_null($creation)) {
    header("Location: patrons.php");
    exit;
}

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

if ($utilisateur->getRole() == 'admin' && !isset($_GET['role']) && !isset($_GET['id_utilisateur'])) {
    $id_page = 1;
    $requete = 'Role de la personne';
    $url = "/creation.php?token=" . $_GET['token'] . "&id_creation=" . $_GET['id_creation'];
} else {
    if (isset($_GET["role"]) && $_GET["role"] == 'user' && !isset($_GET['id_horaire']) && !isset($_GET['id_utilisateur'])) {
        $id_page = 2;
        $requete = 'Horaire de la personne';
        $url = "/form-photo.php?token=" . $_GET['token'] . "&id_creation=" . $_GET['id_creation'];
    } else {
        if (((isset($_GET["role"]) && $_GET["role"] == 'admin') || isset($_GET['id_horaire'])) && !isset($_GET['id_utilisateur'])) {
            $id_page = 3;
            $requete = 'Personne créatrice';
            if (isset($_GET["id_horaire"])) {
                $eleves = getUtilisateurFromIdHoraire($_GET["id_horaire"]);
                $url = "/form-photo.php?token=" . $_GET['token'] . "&id_creation=" . $_GET['id_creation'] . "&role=user";
            } else {
                $eleves = getUtilisateurFromRole('admin');
                $url = "/form-photo.php?token=" . $_GET['token'] . "&id_creation=" . $_GET['id_creation'];
            }
        }
        //Page fin
        else {
            if ($utilisateur->getRole() == 'user' || isset($_GET['id_utilisateur'])) {
                $id_page = 4;
                $requete = 'Image';
                if ($utilisateur->getRole() == 'user') {
                    $url = "/creation.php?token=" . $_GET['token'] . "&id_creation=" . $_GET['id_creation'];
                } else {
                    $url = "/form-photo.php?token=" . $_GET['token'] . "&id_creation=" . $_GET['id_creation'];
                }
            }
        }
    }
}

$res = '';

if (isset($_POST['submit']) && isset($_FILES["image"])) {
    $target_dir_img = __DIR__ . "/images/";
    $target_file_img = $target_dir_img . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file_img, PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES["image"]["size"] > 20000000) {
        $res = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    ) {
        $res = "Sorry, only JPG, JPEG, PNG files are allowed.";
        $uploadOk = 0;
    }

    if (($utilisateur->getRole() == 'admin' && isset($_POST['id_utilisateur']) && !empty($_POST['id_utilisateur'])) || $utilisateur->getRole() == 'user') {
        if ($uploadOk == 1 && isset($_POST['id_creation']) && !empty($_POST['id_creation'])) {
            $target_img = $target_dir_img . $id_creation . "_".count($creation->getImages())."." . $imageFileType;
            $chemin_absolu_img = "/images/" . $id_creation . "_".count($creation->getImages())."." . $imageFileType;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_img)) {
                if ($utilisateur->getRole() == 'user') {
                    $id_image = createImage($chemin_absolu_img, $utilisateur->getIdUtilisateur());
                } else {
                    $id_image = createImage($chemin_absolu_img, $_POST['id_utilisateur']);
                }
                createAssocie($_POST['id_creation'], $id_image);
                $res = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            } else {
                $res = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $res = "Veuillez remplir tous les champs";
    }
} else {
    $res = null;
    $_POST['id_creation'] = null;
    $_POST['id_utilisateur'] = null;
}

$popup = true;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout Image</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "components/navbar.php";
        require "components/card.php";
        $page = 'form-photo';
        $titre = 'Ajouter une image';
        ?>
        <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10 z-20">
            <?php
            if ($id_page == 1) {
                $bouton = 'Suivant';
                require "components/form-role.php";
            }
            if ($id_page == 2) {
                $bouton = 'Suivant';
                require "components/form-horaire.php";
            }
            if ($id_page == 3) {
                $bouton = 'Suivant';
                require "components/form-personne.php";
            }
            if ($id_page == 4) {
                $bouton = 'Valider';
                require "components/form-image.php";
            }
            ?>
        </div>
    </div>

</body>

</html>