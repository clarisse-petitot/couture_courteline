<section>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0 w-[370px]">
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    <?= $titre ?>
                </h1>
                <form class="space-y-4 md:space-y-6" method="GET" action="<?= $page ?>.php">
                    <div>
                        <input type="hidden" value="<?= $_GET["token"] ?>" name='token'>
                    </div>
                    <?php
                    if (isset($_GET['id_horaire'])) {
                    ?>
                        <div>
                            <input type="hidden" value="<?= $_GET["id_horaire"] ?>" name='id_horaire'>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input id="datepicker-autohide" name='date' datepicker datepicker-autohide type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Selectionner la date">
                    </div>
                        <div>
                            <a href="<?= $url ?>"><button type="button" class="w-full text-blue-700 bg-white hover:bg-gray-100 border border-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Retour</button></a>
                        </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center focus:ring-4 focus:outline-none focus:ring-blue-300"><?=$bouton?></button>
                </form>
            </div>
        </div>
    </div>
</section>