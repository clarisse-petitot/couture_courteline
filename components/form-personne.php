<?php
$eleves = getUtilisateurFromIdHoraire($_GET['id_horaire']);
?>

<section>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Déclarer une absence
                </h1>
                <form class="space-y-4 md:space-y-6" method="GET" action="<?=$page?>.php">
                    <div>
                        <input type="hidden" value="<?= $_GET["token"] ?>" name='token'>
                    </div>
                    <div>
                        <input type="hidden" value="<?= $_GET["id_horaire"] ?>" name='id_horaire'>
                    </div>
                    <div>
                        <label for="id_utilisateur" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Personne absente</label>
                        <select id="id_utilisateur" name="id_utilisateur" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php
                            foreach ($eleves as $eleve) {
                            ?>
                                <option value="<?= $eleve->getIdUtilisateur() ?>"><?= $eleve->getNom() . ' ' . $eleve->getPrenom() ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" id="submit" name="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</section>