<?php
$horaires = getAllHoraires();
?>

<section>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0 w-[370px]">
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 <?php if($page=='absence' || $page=='rattrapage'){echo 'bg-blue-100 rounded-lg';}?>">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    <?= $titre ?>
                </h1>
                <form class="space-y-4 md:space-y-6" method='GET' action="<?= $page ?>.php">
                    <div>
                        <input type="hidden" value="<?= $_GET["token"] ?>" name='token'>
                    </div>
                    <?php
                    if (isset($_GET["id_utilisateur"])) {
                    ?>
                        <div>
                            <input type="hidden" value="<?= $_GET["id_utilisateur"] ?>" name='id_utilisateur'>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($_GET["role"])) {
                    ?>
                        <div>
                            <input type="hidden" value="<?= $_GET["role"] ?>" name='role'>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($_GET["id_creation"])) {
                    ?>
                        <div>
                            <input type="hidden" value="<?= $_GET["id_creation"] ?>" name='id_creation'>
                        </div>
                    <?php
                    }
                    ?>
                    <div>
                        <label for="id_horaire" class="block mb-2 text-sm font-medium text-gray-900"><?= $requete ?></label>
                        <select id="id_horaire" name='id_horaire' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <?php
                            foreach ($horaires as $horaire) {
                            ?>
                                <option value="<?= $horaire->getIdHoraire() ?>"><?= $horaire->getJour() . ' ' . $horaire->getHeure() ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    if(!($id_page==1 && ($page=='absence' || $page=='rattrapage')))
                    {?>
                    <div> 
                        <a href="<?= $url ?>"><button type="button" class="w-full text-blue-700 bg-white hover:bg-gray-100 border border-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Retour</button></a>
                    </div>
                    <?php
                    }?>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center focus:ring-4 focus:outline-none focus:ring-blue-300"><?=$bouton?></button>
                </form>
            </div>
        </div>
    </div>
</section>