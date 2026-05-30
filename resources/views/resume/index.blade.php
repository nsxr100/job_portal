<x-layout>
    <h1 class="text-2xl font-bold mb-6">My Resume</h1>

    @if($resume)
        <div class="bg-green-50 p-4 border border-green-200 rounded mb-4">
            <p>Your resume is uploaded:</p>
            <a href="{{ asset('storage/' . $resume->file_path) }}" target="_blank" class="text-blue-600 underline">View Current Resume</a>
        </div>
    @endif
    {{-- 1. SUCCESS MESSAGE --}}
        
    <form action="/resume" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
        @csrf
        <label class="block mb-2">Upload PDF Resume:</label>
        <input type="file" name="resume" accept=".pdf" class="mb-4 block">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
    </form>
</x-layout>