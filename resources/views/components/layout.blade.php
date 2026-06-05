<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <nav class="bg-white shadow-sm border-b p-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">JobPortal</a>

            <div class="flex items-center space-x-6">
                <x-nav-link href="/" :active="request()->is('/')">Browse Jobs</x-nav-link>

                @auth
                    @if(auth()->user()->is_admin)
                        <x-nav-link href="/admin/dashboard" :active="request()->is('admin/*')">Admin</x-nav-link>
                    @elseif(auth()->user()->role === 'employer')
                        <x-nav-link href="/jobs/create" :active="request()->is('jobs/create')">Post a Job</x-nav-link>
                        <x-nav-link href="/employer/dashboard" :active="request()->is('employer/dashboard')">My Dashboard</x-nav-link>
                        <x-nav-link href="/reports" :active="request()->is('reports')">Reports</x-nav-link>
                    @else
                        <x-nav-link href="/applications" :active="request()->is('applications')">My Applications</x-nav-link>
                        <x-nav-link href="/resume" :active="request()->is('resume')">My Resume</x-nav-link>
                    @endif

                    <div class="border-l pl-6 border-gray-300">
                        <span class="text-sm text-gray-500 mr-2">Hi, {{ auth()->user()->name }}</span>
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Logout</button>
                        </form>
                    </div>
                @else
                    <x-nav-link href="/login" :active="request()->is('login')">Login</x-nav-link>
                    <a href="/register" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-6xl mx-auto w-full p-6">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="text-center p-6 text-gray-500 text-sm">
        &copy; {{ date('Y') }} Job Portal Application
    </footer>
</body>
</html>
