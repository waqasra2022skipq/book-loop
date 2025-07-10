{{-- resources/views/livewire/home-page.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-purple-600 via-purple-500 to-blue-400">
    {{-- Header Section --}}
    <div class="relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 bg-gradient-to-r from-purple-600/20 to-blue-400/20">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 50px 50px;"></div>
        </div>
        
        {{-- Main Header Content --}}
        <div class="relative px-4 py-20 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Share Your Stories,<br>
                <span class="text-yellow-300">Read Your Favorites</span>
            </h1>
            
            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search title, Author, Genre" 
                        class="w-full pl-12 pr-16 py-4 text-lg rounded-full bg-white/95 backdrop-blur-sm border-0 shadow-lg focus:ring-4 focus:ring-purple-300 focus:outline-none placeholder-gray-500"
                    >
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                        <button class="p-3 bg-purple-600 text-white rounded-full hover:bg-purple-700 transition-colors shadow-lg">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- Featured Books Carousel --}}
            <div class="flex justify-center items-center space-x-4 mb-8 overflow-x-auto pb-4">
                <div class="flex space-x-4 min-w-max">
                    <div class="transform hover:scale-105 transition-all duration-300 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=120&h=160&fit=crop" 
                             class="w-20 h-28 md:w-24 md:h-32 rounded-lg shadow-lg" alt="Book cover">
                    </div>
                    <div class="transform hover:scale-105 transition-all duration-300 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=120&h=160&fit=crop" 
                             class="w-20 h-28 md:w-24 md:h-32 rounded-lg shadow-lg" alt="Book cover">
                    </div>
                    <div class="transform hover:scale-110 transition-all duration-300 cursor-pointer relative">
                        <img src="https://images.unsplash.com/photo-1592496431122-2349e0fbc666?w=120&h=160&fit=crop" 
                             class="w-24 h-32 md:w-28 md:h-36 rounded-lg shadow-xl border-4 border-yellow-300" alt="Featured book">
                        <div class="absolute -top-2 -right-2 bg-yellow-400 text-purple-800 text-xs font-bold px-2 py-1 rounded-full">
                            NEW
                        </div>
                    </div>
                    <div class="transform hover:scale-105 transition-all duration-300 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=120&h=160&fit=crop" 
                             class="w-20 h-28 md:w-24 md:h-32 rounded-lg shadow-lg" alt="Book cover">
                    </div>
                    <div class="transform hover:scale-105 transition-all duration-300 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=120&h=160&fit=crop" 
                             class="w-20 h-28 md:w-24 md:h-32 rounded-lg shadow-lg" alt="Book cover">
                    </div>
                    <div class="transform hover:scale-105 transition-all duration-300 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=120&h=160&fit=crop" 
                             class="w-20 h-28 md:w-24 md:h-32 rounded-lg shadow-lg" alt="Book cover">
                    </div>
                    <div class="transform hover:scale-105 transition-all duration-300 cursor-pointer">
                        <img src="https://images.unsplash.com/photo-1476275466078-4007374efbbe?w=120&h=160&fit=crop" 
                             class="w-20 h-28 md:w-24 md:h-32 rounded-lg shadow-lg" alt="Book cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Content Section --}}
    <div class="bg-white/10 backdrop-blur-md rounded-t-3xl mx-4 md:mx-8 lg:mx-16 shadow-2xl">
        <div class="p-6 md:p-8">
            <div class="grid md:grid-cols-2 gap-8">
                {{-- Newly Uploaded Section --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Newly uploaded</h2>
                    <div class="space-y-4">
                        @foreach($newlyUploadedBooks as $book)
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer group">
                            <img src="{{ asset('storage/' . $book['cover_image']) }}" 
                                 class="w-16 h-20 rounded-lg shadow-md group-hover:shadow-lg transition-shadow" 
                                 alt="{{ $book['title'] }}">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 group-hover:text-purple-600 transition-colors">
                                    {{ $book['title'] }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $book['description'] }}</p>
                                <div class="flex items-center mt-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Available
                                </div>
                            </div>
                            <button class="px-4 py-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-colors text-sm font-medium">
                                Request
                            </button>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-gray-600 mb-4">Got books to share?</p>
                        <button class="w-full py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-600 transition-all transform hover:scale-105 shadow-lg">
                            Upload Your Books
                        </button>
                    </div>
                </div>
                
                {{-- Popular Section --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Popular</h2>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($popularBooks as $index => $book)
                        <div class="text-center group cursor-pointer">
                            <div class="relative mb-3">
                                <img src="{{ $book['cover'] }}" 
                                     class="w-full h-24 rounded-lg shadow-md group-hover:shadow-lg transition-all transform group-hover:scale-105" 
                                     alt="{{ $book['title'] }}">
                                @if($index < 3)
                                <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center">
                                    {{ $index + 1 }}
                                </div>
                                @endif
                            </div>
                            <h3 class="font-semibold text-sm text-gray-800 group-hover:text-purple-600 transition-colors truncate">
                                {{ $book['title'] }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $book['category'] }}</p>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 text-center">
                        <button class="text-purple-600 font-medium hover:text-purple-700 transition-colors">
                            View All Popular Books →
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- Stats Section --}}
            <div class="mt-8 grid grid-cols-3 gap-4 text-center">
                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">2.5K+</div>
                    <div class="text-sm opacity-80">Books Shared</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">1.2K+</div>
                    <div class="text-sm opacity-80">Active Readers</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-white">
                    <div class="text-2xl font-bold">5K+</div>
                    <div class="text-sm opacity-80">Books Exchanged</div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Call to Action Section --}}
    <div class="text-center py-16 px-4">
        <h2 class="text-3xl font-bold text-white mb-4">Join Our Reading Community</h2>
        <p class="text-white/80 mb-8 max-w-2xl mx-auto">
            Share your books, discover new stories, and connect with fellow book lovers. 
            Every book shared is a new adventure waiting to begin.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button class="px-8 py-3 bg-white text-purple-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors shadow-lg">
                Start Reading
            </button>
            <button class="px-8 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-purple-600 transition-colors">
                Share Your Books
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add any additional JavaScript functionality here
    document.addEventListener('livewire:init', () => {
        Livewire.on('search-books', (event) => {
            console.log('Searching for:', event.search);
            // Implement search logic or redirect to search page
        });
    });
</script>
@endpush