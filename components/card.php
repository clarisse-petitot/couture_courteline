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
                        <img src="<?= $image->getLien() ?>" alt="Thumbnail 1" class="size-16 sm:size-20 object-cover rounded-md cursor-pointer opacity-60 hover:opacity-100 transition duration-300" onclick="changeImage('<?= $image->getLien() ?>', '<?= addslashes($image->getUtilisateur()->getPrenom()) ?>', '<?= addslashes($image->getUtilisateur()->getRole()) ?>', '<?= addslashes($image->getUtilisateur()->getHoraire()->getJour()) ?>', '<?= addslashes($image->getUtilisateur()->getHoraire()->getHeure()) ?>', '<?= addslashes($creation->getDescription()) ?>')">
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
                    <a href="form-photo.php?token=<?= $_GET['token'] ?>&id_creation=<?= $_GET['id_creation'] ?>"><button class="h-[48px] w-[160px] rounded-md bg-blue-700 text-white cursor-pointer ">Ajout une photo</button></a>
                    <a href="#"><button class="h-[48px] w-[190px] rounded-md bg-blue-700 text-white cursor-pointer ">Télécharger le patron</button></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function affiche() {
            console.log('Hello World');
        }

        function changeImage(src, prenom, role, jour, heure, description) {
            console.log(document.getElementById('mainImage').src);
            document.getElementById('mainImage').src = src;
            console.log(document.getElementById('mainImage').src);
            if (role == 'user') {
                document.getElementById('description').innerHTML = description + ' (photo de ' + prenom + ' du ' + jour + ' ' + heure + ')';
            } else {
                document.getElementById('description').innerHTML = description + ' (photo de ' + prenom + ')';
            }
        }
    </script>
</div>