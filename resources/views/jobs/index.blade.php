<x-layout>
    <div class="bg-blue-600 text-white p-12 rounded-lg shadow-md mb-8 text-center">
        <h1 class="text-4xl font-bold">Find Your Dream Job Today</h1>
        <p class="mt-2 text-blue-100 text-lg">Explore open opportunities or post a new position for your company.</p>

        <div class="mt-6">
            @auth
                @if(auth()->user()->is_admin)
                    <a href="/admin/dashboard" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg">
                        Open Admin Dashboard
                    </a>
                @elseif(auth()->user()->role === 'employer')
                    {{-- Only Employers see the "Post a Job" action --}}
                    <a href="/jobs/create" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg">
                        + Post a New Job
                    </a>
                @else
                    {{-- Applicants see a personalized welcome --}}
                    <p class="text-white font-medium">Welcome back, {{ auth()->user()->name }}! Ready to find your next career step?</p>
                @endif
            @else
                {{-- Guests see the Call to Action --}}
                <a href="/register" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg">
                    Sign Up Now
                </a>
            @endauth
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6">Latest Job Openings</h2>
    
    <div class="grid md:grid-cols-2 gap-6">
        @foreach($jobs as $job)
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-xl font-bold mt-2">{{ $job->title }}</h3>
                <p class="text-gray-600 text-sm mt-1">{{ $job->company_name }} • {{ $job->location }}</p>
                
                <div class="flex justify-between items-center mt-6">
                    <span class="font-bold text-gray-900">{{($job->salary) }}</span>
                    <a href="/jobs/{{ $job->id }}" class="text-blue-600 hover:underline font-medium">View Details →</a>
                </div>
            </div>
        @endforeach
    </div>
</x-layout>
