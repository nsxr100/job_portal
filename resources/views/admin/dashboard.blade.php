<x-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Admin Panel</p>
        <h1 class="mt-1 text-3xl font-bold text-gray-900">System Administration Dashboard</h1>
        <p class="mt-2 text-gray-600">Manage all accounts, job postings, resumes, and applications from one protected admin page.</p>
    </div>

    <div class="mb-8 grid gap-4 md:grid-cols-4">
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm font-semibold uppercase text-gray-500">Accounts</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm font-semibold uppercase text-gray-500">Jobs</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $totalJobs }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm font-semibold uppercase text-gray-500">Applications</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $totalApplications }}</p>
        </div>
        <div class="rounded-lg bg-white p-5 shadow">
            <p class="text-sm font-semibold uppercase text-gray-500">Resumes</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $totalResumes }}</p>
        </div>
    </div>

    <section class="mb-8 rounded-lg bg-white p-6 shadow">
        <h2 class="mb-4 text-xl font-bold text-gray-900">Create Account</h2>
        <form action="/admin/users" method="POST" class="grid gap-4 lg:grid-cols-6">
            @csrf
            <input type="text" name="name" placeholder="Name" class="rounded border border-gray-300 p-3" required>
            <input type="email" name="email" placeholder="Email" class="rounded border border-gray-300 p-3" required>
            <input type="password" name="password" placeholder="Password" class="rounded border border-gray-300 p-3" required>
            <select name="role" class="rounded border border-gray-300 p-3">
                <option value="applicant">Applicant</option>
                <option value="employer">Employer</option>
            </select>
            <label class="flex items-center gap-2 rounded border border-gray-300 p-3">
                <input type="checkbox" name="is_admin" value="1">
                <span class="text-sm font-semibold text-gray-700">Admin</span>
            </label>
            <button type="submit" class="rounded bg-blue-600 px-4 py-3 font-bold text-white hover:bg-blue-700">Create</button>
        </form>
        <p class="mt-3 text-sm text-gray-500">If Admin is checked, the account is saved without an applicant/employer role.</p>
    </section>

    <section class="mb-8 overflow-hidden rounded-lg bg-white shadow">
        <div class="border-b p-4">
            <h2 class="text-xl font-bold text-gray-900">All Accounts</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3">User</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Admin</th>
                        <th class="p-3">Jobs</th>
                        <th class="p-3">Applications</th>
                        <th class="p-3">Update Account</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-b align-top">
                            <td class="p-3">
                                <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </td>
                            <td class="p-3">{{ $user->is_admin ? 'Admin only' : ucfirst($user->role ?? 'applicant') }}</td>
                            <td class="p-3">{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                            <td class="p-3">{{ $user->jobs_count }}</td>
                            <td class="p-3">{{ $user->applications_count }}</td>
                            <td class="p-3">
                                <form id="update-user-{{ $user->id }}" action="/admin/users/{{ $user->id }}" method="POST" class="grid gap-2 md:grid-cols-5">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="name" value="{{ $user->name }}" class="rounded border border-gray-300 p-2">
                                    <input type="email" name="email" value="{{ $user->email }}" class="rounded border border-gray-300 p-2">
                                    <input type="password" name="password" placeholder="New password optional" class="rounded border border-gray-300 p-2">
                                    <select name="role" class="rounded border border-gray-300 p-2">
                                        <option value="applicant" @selected($user->role === 'applicant')>Applicant</option>
                                        <option value="employer" @selected($user->role === 'employer')>Employer</option>
                                    </select>
                                    <label class="flex items-center gap-2 rounded border border-gray-300 p-2">
                                        <input type="checkbox" name="is_admin" value="1" @checked($user->is_admin)>
                                        <span>Admin</span>
                                    </label>
                                </form>
                            </td>
                            <td class="p-3">
                                <div class="flex flex-col gap-2">
                                    <form action="/admin/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Delete this user account and related records?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full rounded bg-red-600 px-3 py-2 font-bold text-white hover:bg-red-700" @disabled($user->id === auth()->id())>Delete</button>
                                    </form>
                                    <button type="submit" form="update-user-{{ $user->id }}" class="w-full rounded bg-blue-600 px-3 py-2 font-bold text-white hover:bg-blue-700">Save</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="mb-8 overflow-hidden rounded-lg bg-white shadow">
        <div class="border-b p-4">
            <h2 class="text-xl font-bold text-gray-900">All Job Postings</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3">Job</th>
                        <th class="p-3">Employer</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Applications</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr class="border-b">
                            <td class="p-3">
                                <p class="font-bold text-gray-900">{{ $job->title }}</p>
                                <p class="text-xs text-gray-500">{{ $job->company }} - {{ $job->location }}</p>
                            </td>
                            <td class="p-3">{{ $job->employer?->name ?? 'Deleted user' }}</td>
                            <td class="p-3">{{ $job->type }}</td>
                            <td class="p-3">{{ $job->applications_count }}</td>
                            <td class="p-3">
                                <div class="flex flex-col gap-2">
                                    <a href="/jobs/{{ $job->id }}/edit" class="rounded bg-blue-600 px-3 py-2 text-center font-bold text-white hover:bg-blue-700">Edit</a>
                                    <form action="/admin/jobs/{{ $job->id }}" method="POST" onsubmit="return confirm('Delete this job posting?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full rounded bg-red-600 px-3 py-2 font-bold text-white hover:bg-red-700">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">No job postings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="mb-8 overflow-hidden rounded-lg bg-white shadow">
        <div class="border-b p-4">
            <h2 class="text-xl font-bold text-gray-900">All Applications</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3">Applicant</th>
                        <th class="p-3">Job</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Update</th>
                        <th class="p-3">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr class="border-b">
                            <td class="p-3">
                                <p class="font-bold text-gray-900">{{ $application->applicant?->name ?? 'Deleted user' }}</p>
                                <p class="text-xs text-gray-500">{{ $application->applicant?->email ?? 'No email' }}</p>
                            </td>
                            <td class="p-3">{{ $application->job?->title ?? 'Deleted job' }}</td>
                            <td class="p-3">{{ ucfirst($application->status) }}</td>
                            <td class="p-3">
                                <form action="/admin/applications/{{ $application->id }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded border border-gray-300 p-2">
                                        @foreach(['pending', 'reviewed', 'accepted', 'rejected'] as $status)
                                            <option value="{{ $status }}" @selected($application->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded bg-blue-600 px-3 py-2 font-bold text-white hover:bg-blue-700">Save</button>
                                </form>
                            </td>
                            <td class="p-3">
                                <form action="/admin/applications/{{ $application->id }}" method="POST" onsubmit="return confirm('Delete this application?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded bg-red-600 px-3 py-2 font-bold text-white hover:bg-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">No applications yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="overflow-hidden rounded-lg bg-white shadow">
        <div class="border-b p-4">
            <h2 class="text-xl font-bold text-gray-900">All Resumes</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3">Owner</th>
                        <th class="p-3">File Path</th>
                        <th class="p-3">View</th>
                        <th class="p-3">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resumes as $resume)
                        <tr class="border-b">
                            <td class="p-3">
                                <p class="font-bold text-gray-900">{{ $resume->user?->name ?? 'Deleted user' }}</p>
                                <p class="text-xs text-gray-500">{{ $resume->user?->email ?? 'No email' }}</p>
                            </td>
                            <td class="p-3">{{ $resume->file_path }}</td>
                            <td class="p-3">
                                @if($resume->file_path && $resume->file_path !== '0')
                                    <a href="{{ asset('storage/' . $resume->file_path) }}" target="_blank" class="rounded bg-gray-800 px-3 py-2 font-bold text-white hover:bg-gray-900">View</a>
                                @else
                                    <span class="text-red-600">Missing file</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <form action="/admin/resumes/{{ $resume->id }}" method="POST" onsubmit="return confirm('Delete this resume record and file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded bg-red-600 px-3 py-2 font-bold text-white hover:bg-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">No resumes uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layout>
