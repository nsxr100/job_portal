<x-layout>
    <div class="max-w-5xl mx-auto mt-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">My Applications</h1>
            <a href="/" class="text-blue-600 hover:underline">Browse More Jobs &rarr;</a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($applications->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    <p class="text-lg">You haven't applied to any jobs yet.</p>
                    <a href="/" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Find a Job</a>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <th class="p-4 border-b">Job Title</th>
                            <th class="p-4 border-b">Company</th>
                            <th class="p-4 border-b">Applied On</th>
                            <th class="p-4 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr class="hover:bg-gray-50 transition border-b">
                                <td class="p-4 font-semibold text-gray-900">
                                    <a href="/jobs/{{ $app->job->id }}" class="hover:text-blue-600 hover:underline">
                                        {{ $app->job->title }}
                                    </a>
                                </td>
                                <td class="p-4 text-gray-700">{{ $app->job->company }}</td>
                                <td class="p-4 text-gray-500">{{ $app->created_at->format('M d, Y') }}</td>
                                <td class="p-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                            'accepted' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $color = $statusColors[$app->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $color }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-layout>