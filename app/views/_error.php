<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Erreur <?= $exception->getCode() ?>
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                <?= $exception->getMessage() ?>
            </p>
        </div>
        <div class="text-center">
            <a href="/" class="font-medium text-blue-600 hover:text-blue-500">
                Retourner à l'accueil
            </a>
        </div>
    </div>
</div>
