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
$categories = getCategories();
$res = '';

if (isset($_POST['submit']) && isset($_FILES["image"]) && isset($_FILES["patron_pdf"])) {
    $target_dir_img = __DIR__ . "/../images/";
    $target_dir_patron =  __DIR__ . "/../patrons/";
    $target_file_img = $target_dir_img . basename($_FILES["image"]["name"]);
    $target_file_patron = $target_dir_patron . basename($_FILES["patron_pdf"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file_img, PATHINFO_EXTENSION));
    $patronFileType = strtolower(pathinfo($target_file_patron, PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES["image"]["size"] > 20000000) {
        $res = "Désolé votre fichier est trop lourd";
        $uploadOk = 0;
    }
    if ($_FILES["patron_pdf"]["size"] > 20000000) {
        $res =  "Désolé votre fichier est trop lourd";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    ) {
        $res = "Désolé la photo doit être au format .jpg, .png ou .jpeg";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if (
        $patronFileType != "pdf"
    ) {
        $res = "Désolé le fichier doit être au format .pdf";
        $uploadOk = 0;
    }
    if (isset($_POST['surface_tissu']) && !empty($_POST['surface_tissu']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['tissu']) && !empty($_POST['tissu'])) {
        if ($uploadOk == 1) {
            $id_creation = getDernierIdCreation() + 1;
            $target_img = $target_dir_img . $id_creation . "_0." . $imageFileType;
            $target_patron = $target_dir_patron . $id_creation . "." . $patronFileType;
            $chemin_absolu_img = "/images/" . $id_creation . "_0." . $imageFileType;
            $chemin_absolu_patron = "/patrons/" . $id_creation . "." . $patronFileType;
            createCreation($id_creation, $_POST['nom'], $_POST['description'], $_POST['tissu'], $_POST['surface_tissu'], $chemin_absolu_patron);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_img) && move_uploaded_file($_FILES["patron_pdf"]["tmp_name"], $target_patron)) {
                foreach ($_POST as $key => $value) {
                    if (str_starts_with($key, "categorie-")) {
                        createType($id_creation, substr($key, 10));
                    }
                }
                $id_image = createImage($chemin_absolu_img, $utilisateur->getNom(), $utilisateur->getPrenom());
                createAssocie($id_creation, $id_image);
                header("Location: valider.php?page=patron&token=".$_GET['token']);
                exit;
            } else {
                $res = "Désolé nous n'avons pas plus télécharger les fichiers";
            }
        }
    } else {
        $res = "Veuillez remplir tous les champs";
    }
} else {
    $res = null;
    $_POST["surface_tissu"] = null;
    $_POST["description"] = null;
    $_POST["nom"] = null;
    $_POST["tissu"] = null;
}
$popup = true;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout Patron</title>
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
        ?>
        <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10 z-20">
            <section class="h-[90%]">
                <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0 w-[350px] overflow-y-scroll h-full bg-white rounded-lg">
                    <div class="w-full md:mt-0 sm:max-w-md xl:p-0 h-full">
                        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                                Ajouter un patron
                            </h1>
                            <form class="space-y-4 md:space-y-6" method="POST" enctype="multipart/form-data">
                                <div class="sm:col-span-3">
                                    <label for="nom" class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                                    <div class="mt-2">
                                        <input type="text" name="nom" id="nom" autocomplete="given-name" value="<?= $_POST['nom'] ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div>
                                    <label for="categorie" class="block text-sm font-medium leading-6 text-gray-900 pb-2">Categorie</label>
                                    <?php
                                    foreach ($categories as $categorie) {
                                    ?>
                                        <div class="flex md:items-center items-center justify-start p-2 pr-6">
                                            <input class="w-4 h-4 mr-2 text-blue-700 focus:ring-0" type="checkbox" value="true" id="categorie-<?= $categorie->getIdCategorie() ?>" name="categorie-<?= $categorie->getIdCategorie() ?>" <?php if (isset($_POST["categorie-" . $categorie->getIdCategorie()])) {
                                                                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                                                                        } ?>>
                                            <div class="inline-block">
                                                <div class="flex space-x-6 items-center">
                                                    <label class="mr-2 text-sm leading-3 font-normal text-gray-600" for="categorie-<?= $categorie->getIdCategorie() ?>"><?= $categorie->getNom() ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>

                                <div class="col-span-full">
                                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                    <div class="mt-2">
                                        <textarea id="description" name="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?= $_POST['description'] ?></textarea>
                                    </div>
                                </div>

                                <div class="col-span-full">
                                    <label for="tissu" class="block text-sm font-medium leading-6 text-gray-900">Tissu</label>
                                    <div class="mt-2">
                                        <textarea id="tissu" name="tissu" rows="2" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"><?= $_POST['tissu'] ?></textarea>
                                    </div>
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="surface_tissu" class="block text-sm font-medium leading-6 text-gray-900">Surface de tissu</label>
                                    <div class="mt-2">
                                        <input id="surface_tissu" name="surface_tissu" type="surface_tissu" value="<?= $_POST['surface_tissu'] ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Importer une image</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="image" name="image" type="file" accept="image/png, image/jpeg">
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="patron_pdf">Importer le patron PDF</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="patron_pdf" name="patron_pdf" type="file" accept=".pdf">
                                </div>

                                <?php
                                if ($res != null) {
                                ?>
                                    <p class="text-sm font-medium bg-red-100 p-4 mb-5 text-red-400"><?= $res ?></p>
                                <?php
                                }
                                ?>

                                <div>
                                    <a href="/admin/administration.php?token=<?= $_GET['token'] ?>"><button type="button" class="w-full text-blue-700 bg-white hover:bg-gray-100 border border-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Retour</button></a>
                                </div>
                                <input type="submit" name="submit" id='submit' value='Valider' class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</body>

</html>