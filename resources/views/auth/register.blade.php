<x-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Create an Account</h1>

        <form action="/register" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Full Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Email Address</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">I am a...</label>
                <select name="role" class="w-full border rounded p-2 bg-white" required>
                    <option value="applicant">Job Seeker (Applicant)</option>
                    <option value="employer">Company (Employer)</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                    Register
                </button>
            </div>
            
            <p class="text-center text-sm text-gray-600 mt-4">
                Already have an account? <a href="/login" class="text-blue-600 hover:underline">Log in</a>
            </p>
        </form>
    </div>
</x-layout>