<?php

require_once "../classes.php";
require_once "../fonctions.php";

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

if ($utilisateur->getRole() == 'user') {
    http_response_code(403);
    header("Location: index.php?token=" . $token->getToken());
    exit;
}

$cours_valide = getCours($_GET["id_cours"]);
$allcours = getAllCours();
$bouton = "Voir l'appel";

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between">
        <?php
        require "../components/navbar.php";
        require "../components/cours.php";
        $date_fin = clone $cours_valide->getDate();
        $date_fin->add(new DateInterval('PT2H30M'));
        $appel = getUtilisateurFromCours($cours_valide);
        ?>

        <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10 z-20">
            <div class="max-h-full w-full max-w-xl rounded-2xl bg-white">
                <div class="w-full">
                    <div class="m-14">
                        <div class="mb-8">
                            <h1 class="mb-4 text-3xl font-extrabold"><?= $cours_valide->getHoraire()->getJour() ?> <?= $cours_valide->getDate()->format("d") ?> <?= getTraduction($cours_valide->getDate()) ?></h1>
                            <p class="text-gray-600"> de <?= $cours_valide->getDate()->format("G\hi") ?> à <?= $date_fin->format("G\hi") ?> (<?= count($appel["inscrits"]) + count($appel["rattrapages"]) ?> inscrits)</p>
                        </div>
                        <div>
                            <?php
                            foreach ($appel["inscrits"] as $inscrit) {
                            ?>
                                <p><?= $inscrit->getPrenom() . " " . $inscrit->getNom() ?></p>
                            <?php
                            }
                            if (count($appel["rattrapages"]) > 0) {
                            ?>
                                <hr class="bg-gray-200 lg:w-6/12 w-full md:my-5 my-4" />
                            <?php
                            }
                            foreach ($appel["rattrapages"] as $inscrit) {
                            ?>
                                <p><?= $inscrit->getPrenom() . " " . $inscrit->getNom() . " du " . $inscrit->getHoraire()->getJour() . " " . $inscrit->getHoraire()->getHeure() ?></p>
                            <?php
                            }
                            ?>
                        </div>
                        <a href="accueil.php?token=<?= $_GET["token"] ?>" class="text-blue-700 flex gap-2 transform transition-all hover:text-blue-800 w-fit-content p-5 pb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                            </svg>
                            <span>Retour</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require '../components/footer.php';
        ?>
    </div>

</body>

</html>