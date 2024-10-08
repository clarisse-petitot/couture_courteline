<div>
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap mx-4">
            <!-- Product Images -->
            <div class="w-full md:w-1/2 px-10 mb-8">
                <img src="<?= $creation->getImages()[0]->getLien() ?>" alt="Product" class="w-full h-auto rounded-lg shadow-md mb-4" id="mainImage">
                <div class=" w-full flex gap-4 py-4 justify-left overflow-x-auto">
                    <?php
                    $images = $creation->getImages();
                    foreach ($images as $image) {
                    ?>
                        <img src="<?= $image->getLien() ?>" alt="Thumbnail 1" class="size-16 sm:size-20 object-cover rounded-md cursor-pointer opacity-60 hover:opacity-100 transition duration-300" onclick="changeImage('<?= $image->getLien() ?>', '<?= addslashes($image->getPrenom()) ?>', '<?= addslashes($image->getNom()) ?>', '<?= addslashes($creation->getDescription()) ?>')">
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

                <p class="text-gray-700 mb-6" id="description"><?php echo $creation->getDescription() . ' (photo de ' . $creation->getImages()[0]->getPrenom().' '.$creation->getImages()[0]->getNom().')';?></p>

                <div class="flex flex-col md:flex-row gap-4 mt-6 items-center pb-5 border-b-2 border-gray-300 mb-5">
                    <div class="flex md:ml-6 items-center">
                        <span class="mr-3">Tissu</span>
                        <div class="relative rounded border appearance-none border-gray-400 py-1 focus:outline-none focus:border-red-500 text-base pl-3 pr-3"><?= $creation->getTissu() ?></div>
                    </div>
                    <div class="flex md:ml-6 items-center">
                        <span class="mr-3">Longueur du tissu</span>
                        <div class="relative rounded border appearance-none border-gray-400 py-1 focus:outline-none focus:border-red-500 text-base pl-3 pr-3">
                            <?= $creation->getSurfaceTissu() ?>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 justify-between">
                    <a href="form-photo.php?token=<?= $_GET['token'] ?>&id_creation=<?= $_GET['id_creation'] ?>"><button class="h-[48px] w-[160px] rounded-lg bg-white text-blue-700 cursor-pointer hover:bg-gray-100 border border-blue-800">Ajouter une photo</button></a>
                    <a href="creation.php?token=<?= $_GET['token'] ?>&id_creation=<?= $_GET['id_creation'] ?>&telechargement=true"><button class="h-[48px] w-[190px] rounded-lg bg-blue-700 text-white cursor-pointer hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">Télécharger le patron</button></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeImage(src, prenom, nom, description) {
            document.getElementById('mainImage').src = src;
            document.getElementById('description').innerHTML = description + ' (photo de ' + prenom +' '+ nom + ')';
        }
    </script>
</div>