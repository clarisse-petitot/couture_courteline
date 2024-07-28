<?php

require_once __DIR__ . "/../classes.php";
require_once __DIR__ . "/../fonctions.php";
require_once __DIR__ . "/../extraction/extraction.php";

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

if (isset($_POST['submit']) && isset($_FILES["cours"]) && isset($_FILES["inscrit_email"]) && isset($_FILES["inscrit_cours"])) {
    $target_dir = __DIR__ . "/../extraction/csv/";
    $target_file_1 = $target_dir . basename($_FILES["cours"]["name"]);
    $target_file_2 = $target_dir . basename($_FILES["inscrit_email"]["name"]);
    $target_file_3 = $target_dir . basename($_FILES["inscrit_cours"]["name"]);
    $uploadOk = 1;
    $FileType1 = strtolower(pathinfo($target_file_1, PATHINFO_EXTENSION));
    $FileType2 = strtolower(pathinfo($target_file_2, PATHINFO_EXTENSION));
    $FileType3 = strtolower(pathinfo($target_file_3, PATHINFO_EXTENSION));


    // Check file size
    if ($_FILES["cours"]["size"] > 20000000) {
        $res = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    if ($_FILES["inscrit_email"]["size"] > 20000000) {
        $res =  "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    if ($_FILES["inscrit_cours"]["size"] > 20000000) {
        $res =  "Sorry, your file is too large.";
        $uploadOk = 0;
    }


    // Allow certain file formats
    if (
        $FileType1 != "csv" || $FileType2 != "csv" || $FileType3 != "csv"
    ) {
        $res = "Sorry, only CSV files are allowed.";
        $uploadOk = 0;
    }
    if ($uploadOk == 1) {
        $target_cours = $target_dir . "cours.csv" ;
        $target_inscrit_cours = $target_dir . "inscrit_cours.csv" ;
        $target_inscrit_email = $target_dir . "inscrit_email.csv" ;
        if(file_exists($target_cours))
        {
            unlink($target_cours);
        }
        if(file_exists($target_inscrit_cours))
        {
            unlink($target_inscrit_cours);
        }
        if(file_exists($target_inscrit_email))
        {
            unlink($target_inscrit_email);
        }
        if (move_uploaded_file($_FILES["cours"]["tmp_name"], $target_cours) && move_uploaded_file($_FILES["inscrit_cours"]["tmp_name"], $target_inscrit_cours) && move_uploaded_file($_FILES["inscrit_email"]["tmp_name"], $target_inscrit_email)) {
            $res = "The file " . htmlspecialchars(basename($_FILES["cours"]["name"])) . " has been uploaded.";
            setUtilisateur($target_inscrit_email, $target_inscrit_cours, setCoursHoraire($target_cours));
        } else {
            $res = "Sorry, there was an error uploading your file.";
        }
    }
}

$popup = true;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renouveller une année</title>
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
                                Renouveller une annee
                            </h1>
                            <form class="space-y-4 md:space-y-6" method="POST" enctype="multipart/form-data">

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="cours">Importer les cours de l'année (.csv)</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="cours" name="cours" type="file" accept=".csv">
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="inscrit_email">Importer les inscrit avec leur email (.csv)</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="inscrit_email" name="inscrit_email" type="file" accept=".csv">
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="inscrit_cours">Importer les inscrit avec leur cours (.csv)</label>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="inscrit_cours" name="inscrit_cours" type="file" accept=".csv">
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