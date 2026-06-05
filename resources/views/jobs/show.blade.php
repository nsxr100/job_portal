<x-layout>
    <div class="max-w-3xl mx-auto mt-8">
        <a href="/" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Job Board</a>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-start border-b pb-6 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>
                    <p class="text-xl text-gray-700">{{ $job->company }}</p>
                    <div class="flex gap-4 mt-3 text-sm text-gray-500">
                        <span>📍 {{ $job->location }}</span>
                        <span>💰 {{ $job->salary }}</span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold uppercase">{{ $job->type }}</span>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="/jobs/{{ $job->id }}/edit" class="bg-blue-600 text-white font-bold py-3 px-6 rounded hover:bg-blue-700 shadow-md transition">
                            Edit Job
                        </a>
                    @elseif(auth()->user()->role === 'employer')
                        @if($job->user_id === auth()->id())
                            <a href="/jobs/{{ $job->id }}/edit" class="bg-blue-600 text-white font-bold py-3 px-6 rounded hover:bg-blue-700 shadow-md transition">
                                Edit Job
                            </a>
                        @endif
                    @else
                        <form action="/jobs/{{ $job->id }}/apply" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-6 rounded hover:bg-blue-700 shadow-md transition">
                                Apply Now
                            </button>
                        </form>
                    @endif
                @else
                    <a href="/login" class="bg-gray-800 text-white font-bold py-3 px-6 rounded hover:bg-gray-900 shadow-md transition">
                        Login to Apply
                    </a>
                @endauth
            </div>

            <div>
                <h2 class="text-xl font-bold mb-4">Job Description</h2>
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $job->description }}</p>
            </div>
        </div>
    </div>
</x-layout>
