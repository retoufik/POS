<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant System</title>
    <script src="https://cdn.tailwindcss.com"></script>

    @livewireStyles
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <div>
        @auth
            @livewire('main')
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