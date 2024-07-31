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

if (isset($_GET['telechargement'])) {
    // Initialize a file URL to  
    // the variable  
    $url =  __DIR__ . $creation->getPatron();

    // Use basename() function to  
    // return the file   
    $file_name = $creation->getNameFichier();

    // Vérifier si le fichier existe
    if (file_exists($url)) {
        // Définir les en-têtes appropriés pour le téléchargement
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($url));

        // Nettoyer la mémoire tampon de sortie
        ob_clean();
        flush();

        // Lire le fichier et l'envoyer au navigateur
        readfile($url);
        exit;
    }
}

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
    require './components/card.php';
    require './components/footer.php';
    ?>
</body>

</html>