<div>
    <h3 class="text-2xl font-bold text-gray-800 mb-4">Book Summaries</h3>
    @if($summaries->isEmpty())
        <p class="text-gray-600 text-lg">No summaries available yet. Be the first to add one!</p>
    @else
        <div class="space-y-6">
            @foreach($summaries as $summary)
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 shadow">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($summary->writer?->name,0,1)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">{{ $summary->writer?->name }}</div>
                            <div class="text-xs text-gray-500">{{ $summary->created_at->diffForHumans() }}</div>
                        </div>
                        @if($summary->rating)
                            <div class="ml-auto flex items-center gap-1">
                                <span class="text-yellow-500 font-bold">&#9733;</span>
                                <span class="text-sm font-semibold">{{ $summary->rating }}/5</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-gray-700 text-base leading-relaxed mt-2">{{ $summary->summary }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
