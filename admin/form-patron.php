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

if (isset($_POST['submit'])) {
    if (isset($_POST['surface_tissu']) && !empty($_POST['surface_tissu']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['tissu']) && !empty($_POST['tissu'])) {
        createUtilisateur($_POST['nom'], $_POST['prenom'], $_POST['surface_tissu'], $_POST['id_horaire'], $_GET['role']);
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
    <title>Ajout Patrons</title>
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
                            <form class="space-y-4 md:space-y-6" method='POST'>
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
                                            <input class="w-4 h-4 mr-2 text-blue-700 focus:ring-0" type="checkbox" value="true" id="categorie-<?= $categorie->getIdCategorie() ?>" name="categorie-<?= $categorie->getIdCategorie() ?>">
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
                                    <a href="/admin/administration.php?token=<?=$_GET['token']?>"><button type="button" class="w-full text-blue-700 bg-white hover:bg-gray-100 border border-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Retour</button></a>
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