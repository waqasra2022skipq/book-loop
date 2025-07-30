<section class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl border border-blue-100 p-8 sm:p-12">
        <h1 class="text-3xl font-extrabold text-blue-700 mb-6">User Queries & Suggestions</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-lg">
                <thead>
                    <tr class="bg-blue-50">
                        <th class="px-4 py-2 text-left text-sm font-semibold text-blue-700">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-blue-700">Email</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-blue-700">Type</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-blue-700">Subject</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-blue-700">Message</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-blue-700">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-blue-700">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queries as $query)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $query->name }}</td>
                            <td class="px-4 py-2">{{ $query->email }}</td>
                            <td class="px-4 py-2 capitalize">{{ $query->type }}</td>
                            <td class="px-4 py-2">{{ $query->subject }}</td>
                            <td class="px-4 py-2 text-gray-700">{{ $query->message }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $query->status === 'new' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($query->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-xs text-gray-500">{{ $query->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">No queries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $queries->links() }}
        </div>
    </div>
</section>
