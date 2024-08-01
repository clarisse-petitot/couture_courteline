<div class="flex flex-col p-10 md:flex-row gap-10 w-full justify-center items-center bg-white rounded-lg">
    <div class="flex flex-col gap-12 px-5">
        <h3 class="font-black text-6xl text-purple-900"><?= $message ?></h3>
        <p>
            <?= $desc ?>
        </p>
        <a href="<?=$retour?>" class="text-purple-900 flex gap-2 transform transition-all hover:scale-110 hover:text-purple-800 w-fit-content">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
            </svg>
            <span>Retour</span>
        </a>
    </div>
    <div>
        <img src="/logo_courteline.png" class="h-32 lg:h-48 px-5" alt="logo cp">
    </div>
</div>