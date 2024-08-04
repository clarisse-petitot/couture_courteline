<section>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0 w-[400px]">
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    <?= $titre ?>
                </h1>
                <form class="space-y-4 md:space-y-6" method='POST'>
                    <div class="sm:col-span-3">
                        <label for="nom" class="block text-sm font-medium leading-6 text-gray-900">Nom</label>
                        <div class="mt-2">
                            <input type="text" name="nom" id="nom" autocomplete="given-name" value="<?= $_POST['nom'] ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="prenom" class="block text-sm font-medium leading-6 text-gray-900">Prenom</label>
                        <div class="mt-2">
                            <input type="text" name="prenom" id="prenom" value="<?= $_POST['prenom'] ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse Email</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" value="<?= $_POST['email'] ?>" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <?php
                    if ($_GET['role'] == 'user') {
                        $horaires = getAllHoraires();
                    ?>
                        <div>
                            <label for="id_horaire" class="block mb-2 text-sm font-medium text-gray-900">Cours de la personne</label>
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
                    }
                    ?>
                    <div> 
                        <a href="<?= $url ?>"><button type="button" class="w-full text-blue-700 bg-white hover:bg-gray-100 border border-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Retour</button></a>
                    </div>
                    <input type="submit" name="submit" id='submit' value='Valider' class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center focus:ring-4 focus:outline-none focus:ring-blue-300">
                </form>
            </div>
        </div>
    </div>
</section>