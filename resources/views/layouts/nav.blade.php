<nav class="bg-blue-darker mb-6 py-4">
    <div class="container mx-auto flex justify-between items-center text-blue-lightest">
        <div>
            <h1 class="font-normal text-2xl">
                <a href="/" class="text-blue-lightest flex items-center">
                    @include ('svgs.logo', ['class' => 'mr-2'])
                    {{ config('app.name', 'Council') }}
                </a>
            </h1>
        </div>

        <div>
            <div>Search</div>
        </div>
    </div>
</nav>
