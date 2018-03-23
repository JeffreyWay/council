<nav class="bg-blue-dark py-4">
    <div class="container mx-auto flex justify-between items-center text-blue-lightest pl-6">
        <div>
            <h1 class="font-normal text-2xl">
                Welcome, {{ auth()->user()->name }}!
            </h1>
        </div>
    </div>
</nav>
