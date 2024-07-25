<?php
$horaires = getAllHoraires();
?>

<section>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    <?= $titre ?>
                </h1>
                <form class="space-y-4 md:space-y-6" method='GET' action="<?= $page ?>.php">
                    <div>
                        <input type="hidden" value="<?= $_GET["token"] ?>" name='token'>
                    </div>
                    <div>
                        <label for="id_horaire" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cours de la personne</label>
                        <select id="id_horaire" name='id_horaire' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php
                            foreach ($horaires as $horaire) {
                            ?>
                                <option value="<?= $horaire->getIdHoraire() ?>"><?= $horaire->getJour() . ' ' . $horaire->getHeure() ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" id="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Suivant</button>
                </form>
            </div>
        </div>
    </div>
</section>