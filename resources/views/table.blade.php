<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant System</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
<style>
.floor-pattern {
    background-image: radial-gradient(circle at 1px 1px, #3E3B5B 1px, transparent 0);
    background-size: 40px 40px;
}

.table-container:hover {
    z-index: 10;
    transform: translate(-50%, -50%) scale(1.1);
}

.animate-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(232, 124, 111, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(232, 124, 111, 0); }
    100% { box-shadow: 0 0 0 0 rgba(232, 124, 111, 0); }
}
</style>
    @livewireStyles
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <div>
        @auth
            @livewire('table-map')
        @else
            <div class="flex flex-col items-center justify-center min-h-screen p-6 space-y-4">
                <h2 class="text-xl font-semibold">Please Log In or Register</h2>
                <ul class="space-x-4">
                    <li class="inline-block">
                        <a href="{{ route('login') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                            Log In
                        </a>
                    </li>
                    <li class="inline-block">
                        <a href="{{ route('register') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition">
                            Sign Up
                        </a>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
    @livewireScripts
</body>
</html>