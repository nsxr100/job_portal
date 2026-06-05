<x-layout>
    <div class="max-w-6xl mx-auto mt-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Employer Dashboard</h1>
            <div class="flex gap-3">
                <a href="/reports" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 font-bold transition">
                    Reports
                </a>
                <a href="/jobs/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold transition">
                    Post a New Job
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-4 border-b">
                <h2 class="text-xl font-bold text-gray-900">My Job Postings</h2>
            </div>
            @if(($employerJobs ?? collect())->isEmpty())
                <div class="p-8 text-center text-gray-500">You have not posted a job yet.</div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-800 text-white uppercase text-sm">
                            <th class="p-4 border-b">Title</th>
                            <th class="p-4 border-b">Company</th>
                            <th class="p-4 border-b">Applications</th>
                            <th class="p-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($employerJobs as $job)
                            <tr class="hover:bg-gray-50 transition border-b">
                                <td class="p-4">
                                    <a href="/jobs/{{ $job->id }}" class="font-semibold text-blue-600 hover:underline">{{ $job->title }}</a>
                                </td>
                                <td class="p-4">{{ $job->company }}</td>
                                <td class="p-4">{{ $job->applications_count }}</td>
                                <td class="p-4 flex gap-2">
                                    <a href="/jobs/{{ $job->id }}/edit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm font-bold hover:bg-blue-700 transition">Edit</a>
                                    <form action="/jobs/{{ $job->id }}" method="POST" onsubmit="return confirm('Delete this job posting?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm font-bold hover:bg-red-700 transition">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>


        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($applications->isEmpty())
                <div class="p-10 text-center text-gray-500">
                    <p class="text-lg mb-2">No one has applied to your jobs yet.</p>
                    <p class="text-sm">When applicants submit their resumes, they will appear here.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-800 text-white uppercase text-sm">
                            <th class="p-4 border-b">Applicant Name</th>
                            <th class="p-4 border-b">Applied For</th>
                            <th class="p-4 border-b">Resume</th>
                            <th class="p-4 border-b">Status</th>
                            <th class="p-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($applications as $app)
                            <tr class="hover:bg-gray-50 transition border-b">
                                <td class="p-4">
                                    <p class="font-bold text-gray-900">{{ $app->applicant->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $app->applicant->email }}</p>
                                </td>
                                
                                <td class="p-4">
                                    <a href="/jobs/{{ $app->job->id }}" class="font-semibold text-blue-600 hover:underline">
                                        {{ $app->job->title }}
                                    </a>
                                </td>
                                
                                <td class="p-4">
                                    @if($app->applicant->resume)
                                        <a href="{{ asset('storage/' . $app->applicant->resume->file_path) }}" target="_blank" class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 py-1 px-3 rounded inline-flex items-center transition">
                                            📄 View PDF
                                        </a>
                                    @else
                                        <span class="text-xs text-red-500 italic">No resume</span>
                                    @endif
                                </td>
                                
                                <td class="p-4">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                            'accepted' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $badgeColor = $colors[$app->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $badgeColor }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                
                                <td class="p-4 flex gap-2">
                                    <form action="/applications/{{ $app->id }}/status" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm font-bold hover:bg-green-600 transition" @if($app->status == 'accepted') disabled opacity-50 cursor-not-allowed @endif>
                                            Accept
                                        </button>
                                    </form>

                                    <form action="/applications/{{ $app->id }}/status" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm font-bold hover:bg-red-600 transition" @if($app->status == 'rejected') disabled opacity-50 cursor-not-allowed @endif>
                                            Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-layout>
