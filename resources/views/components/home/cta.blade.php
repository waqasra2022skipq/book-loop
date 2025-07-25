<div class="w-full px-4 py-12 flex flex-col items-center text-center bg-white">
    
    <h2 class="text-2xl sm:text-3xl font-bold text-blue-700 mb-3">Ready to start sharing?</h2>
    @if (auth()->check())
        <p class="text-base text-gray-600 mb-6">Join our community of book lovers and start sharing your favorite reads today.</p>
        <a href="{{ route('books.create') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition">Add Your First Book</a>
    @else
        <p class="text-base text-gray-600 mb-6">Join Book Loop today and connect with fellow book lovers.</p>
        <a href="{{ route('register') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition">Sign Up Free</a>
    @endif
</div>
