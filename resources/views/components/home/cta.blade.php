<div class="w-full px-3 sm:px-4 py-8 sm:py-12 flex flex-col items-center text-center bg-white">
    
    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-blue-700 mb-2 sm:mb-3 px-2">Ready to start sharing?</h2>
    @if (auth()->check())
        <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6 px-4 leading-relaxed">Join our community of book lovers and start sharing your favorite reads today.</p>
        <a href="{{ route('books.create') }}" class="inline-block px-6 sm:px-8 py-2.5 sm:py-3 bg-blue-600 text-white text-sm sm:text-base rounded-lg shadow hover:bg-blue-700 transition-all duration-200 font-medium">Add Your First Book</a>
    @else
        <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6 px-4 leading-relaxed">Join Book Loop today and connect with fellow book lovers.</p>
        <a href="{{ route('register') }}" class="inline-block px-6 sm:px-8 py-2.5 sm:py-3 bg-blue-600 text-white text-sm sm:text-base rounded-lg shadow hover:bg-blue-700 transition-all duration-200 font-medium">Sign Up Free</a>
    @endif
</div>
