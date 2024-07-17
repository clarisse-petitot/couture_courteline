<?php

require_once "classes.php";
require_once "fonctions.php";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <?php
    if (isset($_POST['submit'])) {
        if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['prenom']) && !empty($_POST['prenom']) && estInscrit($_POST['email'], $_POST['nom'], $_POST['prenom'])[0] == 0) {
            require_once 'mail/mail_service.php';
            $utilisateur = estInscrit($_POST['email'], $_POST['nom'], $_POST['prenom'])[1];
            $token = new Token( uniqidReal(),
            time(),
            $utilisateur
            );
            createToken($token);
            sendMail($utilisateur,$token);
            $res = "succes";
        } else {
            if (estInscrit($_POST['email'], $_POST['nom'], $_POST['prenom'])[0] == 1) {
                $res = "Votre adresse email ne correspond pas";
            } else {
                if (estInscrit($_POST['email'], $_POST['nom'], $_POST['prenom'])[0] == 2) {
                    $res = "Votre nom ou prenom ne correspond pas";
                }
            }
        }
    } else {
        $res = null;
        $_POST["email"] = null;
        $_POST["nom"] = null;
        $_POST["prenom"] = null;
    }
    ?>

    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-52 mr-2" src="logo_courteline.png" alt="logo">
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Accéder à son compte de couture
                    </h1>
                    <form class="space-y-4 md:space-y-6" method="POST">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5" placeholder="jean@gmail.com" value="<?=$_POST["email"]?>">
                        </div>
                        <div>
                            <label for="nom" class="block mb-2 text-sm font-medium text-gray-900">Nom</label>
                            <input type="text" name="nom" id="nom" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5" value="<?=$_POST["nom"]?>" placeholder="Dupuis">
                        </div>
                        <div>
                            <label for="prenom" class="block mb-2 text-sm font-medium text-gray-900">Prenom</label>
                            <input type="text" name="prenom" id="prenom" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5" value="<?=$_POST["prenom"]?>" placeholder="Jean">
                        </div>
                        <?php
                        if ($res == "succes") {
                        ?>
                            <p class="text-sm font-medium bg-green-100 p-4 mb-5 text-green-400">Mail envoyé avec succès</p>
                            <?php
                        } else {
                            if ($res != null) {
                            ?>
                                <p class="text-sm font-medium bg-red-100 p-4 mb-5 text-red-400"><?= $res ?></p>
                        <?php
                            }
                        }
                        ?>
                        <button type="submit" id="submit" name="submit" class="w-full text-white bg-sky-700 hover:bg-sky-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

</body>

</html>