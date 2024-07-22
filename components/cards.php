    <div class="py-5 lg:mx-auto lg:max-w-7xl lg:px-8">
        <div class="relative">
            <div class="relative w-full overflow-x-auto px-20">
                <ul role="list" class="inline-flex mx-0 grid sm:grid-cols-1 space-x-0 md:grid-cols-2 md:gap-x-10 lg:grid-cols-3 lg:gap-x-10">

                    <?php
                    foreach ($creations as $creation) {
                    ?>
                        <a href="creation.php?id_creation=<?= $creation->getIdCreation() ?>">
                            <li class="inline-flex flex-col text-center w-auto p-3 bg-black/[.15] m-3 rounded-md ">
                                <div class="group relative">
                                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200">
                                    <img src="img/creations/<?= $creation->getImages()[0]->getIdImage() ?>.png" alt="image représentant : <?= $creation->getNom() ?>" class="h-full w-full object-cover object-center group-hover:opacity-75">
                                </div>
                                <div class="mt-3">
                                    <h3 class="mt-1 font-semibold text-gray-900">
                                            <span class="absolute inset-0"></span>
                                            <?= $creation->getNom() ?>
                                    </h3>
                                </div>
                                </div>
                            </li>
                        </a>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>