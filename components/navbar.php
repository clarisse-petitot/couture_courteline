<nav class="border-gray-200 sticky top-0 z-10 bg-white w-full">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="/index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="/logo_courteline.png" class="h-12" alt="Logo de Courteline" />
        </a>
        <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center rounded-lg md:hidden hover:bg-gray-100 focus:outline-none" aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                <?php
                if ($utilisateur->getRole() == 'admin') {
                ?>
                    <li>
                        <a href="/admin/accueil.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 " aria-current="page">Accueil</a>
                    </li>
                    <li>
                        <a href="/admin/absence.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 ">Absence</a>
                    </li>
                    <li>
                        <a href="/admin/rattrapage.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 ">Rattrapage</a>
                    </li>
                    <li>
                        <a href="/patrons.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 ">Patrons</a>
                    </li>
                    <li>
                        <a href="/admin/administration.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">Administration</a>
                    </li>
                <?php
                } else {
                ?>
                    <li>
                        <a href="/index.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 " aria-current="page">Accueil</a>
                    </li>
                    <li>
                        <a href="/absences.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 ">Prochains Cours</a>
                    </li>
                    <li>
                        <a href="/rattrapages.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 ">Rattrapages</a>
                    </li>
                    <li>
                        <a href="/patrons.php?token=<?= $_GET["token"] ?>" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0">Patrons</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>