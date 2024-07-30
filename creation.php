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

?>

<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $creation->getNom() ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body class="min-h-screen flex flex-col justify-between">
    <?php
    require './components/navbar.php';
    ?>

    <div>
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-wrap mx-4">
                <!-- Product Images -->
                <div class="w-full md:w-1/2 px-10 mb-8">
                    <img src="<?= $creation->getImages()[0]->getLien() ?>" alt="Product" class="w-full h-auto rounded-lg shadow-md mb-4" id="mainImage">
                    <div class="flex gap-4 py-4 justify-center overflow-x-auto">
                        <?php
                        $images = $creation->getImages();
                        foreach ($images as $image) {
                        ?>
                            <img src="<?= $image->getLien() ?>" alt="Thumbnail 1" class="size-16 sm:size-20 object-cover rounded-md cursor-pointer opacity-60 hover:opacity-100 transition duration-300" onclick="changeImage($this->scr, <?= $image->getUtilisateur()->getPrenom() ?>, <?= $image->getUtilisateur()->getRole() ?>, <?= $image->getUtilisateur()->getHoraire()->getJour() ?>, <?= $image->getUtilisateur()->getHoraire()->getHeure() ?>, <?= $creation->getDescription() ?>)">
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="w-full md:w-1/2 px-4">
                    <h2 class="text-3xl font-bold mb-2"><?= $creation->getNom() ?></h2>

                    <div class="flex mb-4">
                        <span class="flex items-center space-x-2">
                            <?php
                            for ($i = 0; $i < count($creation->getCategories()); $i++) {
                            ?>
                                <div class="w-full h-7 rounded-full flex items-center justify-center bg-blue-100 px-3 my-2 text-sm">
                                    <?= $creation->getCategories()[$i]->getNom() ?>
                                </div>
                            <?php
                            }
                            ?>
                        </span>
                    </div>

                    <p class="text-gray-700 mb-6" id="description"><?php echo $creation->getDescription() . ' (photo de ' . $creation->getImages()[0]->getUtilisateur()->getPrenom();
                                                                    if ($creation->getImages()[0]->getUtilisateur()->getRole() == 'user') {
                                                                        echo ' du ' . $creation->getImages()[0]->getUtilisateur()->getHoraire()->getJour() . ' ' . $creation->getImages()[0]->getUtilisateur()->getHoraire()->getHeure();
                                                                    }
                                                                    echo ')'; ?></p>

                    <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-300 mb-5">
                        <div class="flex ml-6 items-center">
                            <span class="mr-3">Tissu</span>
                            <div class="relative rounded border appearance-none border-gray-400 py-1 focus:outline-none focus:border-red-500 text-base pl-3 pr-3"><?= $creation->getTissu() ?></div>
                        </div>
                        <div class="flex ml-6 items-center">
                            <span class="mr-3">Longueur du tissu</span>
                            <div class="relative rounded border appearance-none border-gray-400 py-1 focus:outline-none focus:border-red-500 text-base pl-3 pr-3">
                                <?= $creation->getSurfaceTissu() ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <span class="title-font font-medium text-2xl text-gray-900"></span>
                        <a href="#" target="_blank"><button class="h-[48px] w-[175px] rounded-md bg-blue-700 text-white cursor-pointer ">Télécharger le patron</button></a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function changeImage(scr, prenom, role, jour, heure, description) {
                document.getElementById('mainImage').scr = src;
                if (role == 'user') {
                    document.getElementById('description').innerHTML = description + ' (photo de ' + prenom + ' du ' + jour + ' ' + heure + ')';
                }
                else{
                    document.getElementById('description').innerHTML = description + ' (photo de ' + prenom + ')';
                }
            }
        </script>
    </div>



    <?php
    require './components/footer.php';
    ?>
</body>

</html>