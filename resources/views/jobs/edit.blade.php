<x-layout>
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Job</h1>

        <form action="/jobs/{{ $job->id }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Job Title</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" class="w-full rounded border border-gray-300 p-3" required>
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Company</label>
                    <input type="text" name="company" value="{{ old('company', $job->company) }}" class="w-full rounded border border-gray-300 p-3" required>
                    @error('company') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $job->location) }}" class="w-full rounded border border-gray-300 p-3" required>
                    @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Salary</label>
                    <input type="text" name="salary" value="{{ old('salary', $job->salary) }}" class="w-full rounded border border-gray-300 p-3" required>
                    @error('salary') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full rounded border border-gray-300 p-3" required>
                        @foreach(['Full-Time', 'Part-Time', 'Contract', 'Remote'] as $type)
                            <option value="{{ $type }}" @selected(old('type', $job->type) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category', $job->category) }}" class="w-full rounded border border-gray-300 p-3">
                    @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="8" class="w-full rounded border border-gray-300 p-3" required>{{ old('description', $job->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded bg-blue-600 px-5 py-2 font-semibold text-white hover:bg-blue-700">Save Changes</button>
                <a href="/employer/dashboard" class="rounded bg-gray-200 px-5 py-2 font-semibold text-gray-700 hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-layout>
