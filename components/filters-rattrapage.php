<script>
    function showFilters() {
        var fSection = document.getElementById("filterSection");
        if (fSection.classList.contains("hidden")) {
            fSection.classList.remove("hidden");
            fSection.classList.add("block");
        } else {
            fSection.classList.add("hidden");
        }
    }

    function closeFilterSection() {
        var fSection = document.getElementById("filterSection");
        fSection.classList.add("hidden");
    }
</script>

<div class="2xl:container 2xl:mx-auto px-4 lg:px-20 md:px-6">

    <div class="md:py-6 py-5">
        <div class="flex justify-between items-center">
            <h2 class="lg:text-4xl text-3xl lg:leading-9 leading-7 text-gray-800 font-semibold">Vous avez <?= $utilisateur->getNbrRattrapage() ?> cours à rattraper</h2>
            <!-- filters Button (md and plus Screen) -->
            <button onclick="showFilters()" class="sm:flex hidden cursor-pointer h-[48px] w-[120px] rounded-md bg-blue-700 text-white cursor-pointer justify-center items-center">
                <svg class="mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12C7.10457 12 8 11.1046 8 10C8 8.89543 7.10457 8 6 8C4.89543 8 4 8.89543 4 10C4 11.1046 4.89543 12 6 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 4V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M6 12V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 18C13.1046 18 14 17.1046 14 16C14 14.8954 13.1046 14 12 14C10.8954 14 10 14.8954 10 16C10 17.1046 10.8954 18 12 18Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 4V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 18V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M18 9C19.1046 9 20 8.10457 20 7C20 5.89543 19.1046 5 18 5C16.8954 5 16 5.89543 16 7C16 8.10457 16.8954 9 18 9Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M18 4V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M18 9V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Filtres
            </button>
        </div>

        <!-- Filters Button (Small Screen) -->

        <button onclick="showFilters()" class="cursor-pointer mt-6 block sm:hidden py-2 w-full bg-blue-700 flex text-base leading-4 font-normal text-white justify-center items-center">
            <svg class="mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 12C7.10457 12 8 11.1046 8 10C8 8.89543 7.10457 8 6 8C4.89543 8 4 8.89543 4 10C4 11.1046 4.89543 12 6 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6 4V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6 12V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 18C13.1046 18 14 17.1046 14 16C14 14.8954 13.1046 14 12 14C10.8954 14 10 14.8954 10 16C10 17.1046 10.8954 18 12 18Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 4V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 18V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M18 9C19.1046 9 20 8.10457 20 7C20 5.89543 19.1046 5 18 5C16.8954 5 16 5.89543 16 7C16 8.10457 16.8954 9 18 9Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M18 4V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M18 9V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Filtres
        </button>
    </div>

    <div id="filterSection" class="hidden block relative md:py-5 lg:px-20 md:px-6 py-4 px-4 w-full">
        <!-- Cross button Code -->
        <div onclick="closeFilterSection()" class="cursor-pointer text-gray-800 absolute right-0 top-0 md:py-10 lg:px-20 md:px-6 py-9 px-4">
            <svg class="lg:w-5 lg:h-5 w-3 h-3" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25 1L1 25" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1 1L25 25" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <form action="rattrapages.php">
            <div>
                <input type="hidden" value="<?= $_GET["token"] ?>" name='token'>
            </div>
            <!-- Jour Section -->
            <div>
                <div class="flex items-center space-x-2 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                    <p class="lg:text-lg text-base lg:leading-6 leading-5 font-normal ">Jour</p>
                </div>
                <div class="flex flex-wrap mt-4 max-w-lg">
                    <?php
                    foreach ($jours as $jour) {
                    ?>
                        <div class="flex md:justify-center md:items-center items-center justify-start p-2 pr-6">
                            <input class="w-4 h-4 mr-2 text-blue-700 focus:ring-0" type="checkbox" value="true" id="jour-<?= $jour ?>" name="jour-<?= $jour ?>" <?php if (in_array($jour, $filtres["jours"])) { ?>checked<?php } ?> />
                            <div class="inline-block">
                                <div class="flex space-x-6 justify-center items-center">
                                    <label class="mr-2 text-sm leading-3 font-normal text-gray-600" for="jour-<?= $jour ?>"><?= $jour ?></label>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <hr class="bg-gray-200 lg:w-6/12 w-full md:my-5 my-4" />

            <!-- Horaire Section -->
            <div>
                <div class="flex items-center space-x-2 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="lg:text-lg text-base lg:leading-6 leading-5 font-normal ">Horaire</p>
                </div>
                <div class="flex flex-wrap mt-4 max-w-lg">
                    <?php
                    foreach ($heures as $heure) {
                    ?>
                        <div class="flex md:justify-center md:items-center items-center justify-start p-2 pr-6">
                            <input class="w-4 h-4 mr-2 text-blue-700 focus:ring-0" type="checkbox" id="heure-<?= $heure ?>" name="heure-<?= $heure ?>" <?php if (in_array($heure, $filtres["heures"])) { ?>checked<?php } ?> />
                            <div class="inline-block">
                                <div class="flex space-x-6 justify-center items-center">
                                    <label class="text-sm leading-3 font-normal text-gray-600" for="heure-<?= $heure ?>"><?= $heure ?></label>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Apply Filter Button (Large Screen) -->

            <div class="hidden md:block absolute right-0 bottom-0 md:py-5 py-5">
                <input type="submit" value="Valider" class="h-[48px] w-[120px] rounded-md bg-blue-700 text-white cursor-pointer">
            </div>
            <!-- Reset Filter Button (Table or lower Screen) -->

            <div class="block md:hidden w-full mt-5">
                <a href="/rattrapages.php?token=<?= $_GET["token"] ?>"><button type="button" class="w-full text-base leading-4 font-normal py-3 px-5 bg-gray-100 text-blue-700 border border-2 border-blue-700">Réinitialiser</button></a>
            </div>

            <!-- Apply Filter Button (Table or lower Screen) -->

            <div class="block md:hidden w-full mt-5">
                <input type="submit" value="Valider" class="w-full text-base leading-4 font-normal py-3 px-5 text-white bg-blue-700">
            </div>

            <input type="hidden" value="1" name="id_page">
        </form>

        <!-- Reset Filter Button (Large Screen) -->
        <div class="hidden md:block absolute right-[135px] bottom-0 md:py-5 py-5">
            <a href="/rattrapages.php?token=<?= $_GET["token"] ?>"><button type="button" class="h-[48px] w-[110px] border border-2 rounded-md border-blue-600 bg-gray-100 text-blue-700 ">Réinitialiser</button></a>
        </div>

    </div>
</div>
<style>
    .checkbox:checked+.check-icon {
        display: flex;
    }
</style>