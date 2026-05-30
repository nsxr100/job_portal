<x-layout>
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md mt-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">Post a New Job</h1>

        <form action="/jobs" method="POST" class="space-y-4">
            @csrf <div>
                <label class="block text-gray-700 font-bold mb-2">Job Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded p-2" placeholder="e.g. Senior Laravel Developer">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Company Name</label>
                    <input type="text" name="company" value="{{ old('company') }}" class="w-full border rounded p-2">
                    @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded p-2" placeholder="e.g. Remote, New York, etc.">
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Salary</label>
                    <input type="text" name="salary" value="{{ old('salary') }}" class="w-full border rounded p-2" placeholder="e.g. $80,000 - $100,000">
                    @error('salary') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Job Type</label>
                    <select name="type" class="w-full border rounded p-2 bg-white">
                        <option value="Full-Time" {{ old('type') == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                        <option value="Part-Time" {{ old('type') == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                        <option value="Contract" {{ old('type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Freelance" {{ old('type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Job Category</label>
                <input type="text" name="category" value="{{ old('category') }}" class="w-full border rounded p-2" placeholder="e.g. Engineering, Design, Sales">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" rows="6" class="w-full border rounded p-2">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded hover:bg-blue-700 transition">
                    Publish Job Listing
                </button>
            </div>
        </form>
    </div>
</x-layout>