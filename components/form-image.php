<div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 p-10 z-20">
    <section>
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0 w-[350px] overflow-y-scroll h-full bg-white rounded-lg">
            <div class="w-full md:mt-0 sm:max-w-md xl:p-0 h-full">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Ajouter une image
                    </h1>
                    <form class="space-y-4 md:space-y-6" method="POST" enctype="multipart/form-data">

                        <?php
                        if (isset($_GET["id_creation"])) {
                        ?>
                            <div>
                                <input type="hidden" value="<?= $_GET["id_creation"] ?>" name='id_creation'>
                            </div>
                        <?php
                        }
                        ?>

                        <?php
                        if (isset($_GET["id_utilisateur"])) {
                        ?>
                            <div>
                                <input type="hidden" value="<?= $_GET["id_utilisateur"] ?>" name='id_utilisateur'>
                            </div>
                        <?php
                        }
                        ?>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="cours">Importer votre image</label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="image" name="image" type="file" accept="image/png, image/jpeg">
                        </div>

                        <?php
                        if ($res != null) {
                        ?>
                            <p class="text-sm font-medium bg-red-100 p-4 mb-5 text-red-400"><?= $res ?></p>
                        <?php
                        }
                        ?>

                        <div>
                            <a href="<?= $url ?>"><button type="button" class="w-full text-blue-700 bg-white hover:bg-gray-100 border border-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Retour</button></a>
                        </div>
                        <input type="submit" name="submit" id='submit' value='Valider' class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>