<section class="p-3 sm:p-4 space-y-4 sm:space-y-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 sm:px-4 sm:py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
        <!-- As Borrower Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <div class="flex flex-col space-y-2">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-lg sm:text-xl font-bold text-gray-900">{{ $statistics['as_borrower']['active'] }}</div>
                    </div>
                </div>
                <div class="text-xs sm:text-sm font-medium text-gray-600">Borrowed</div>
            </div>
        </div>

        <!-- As Owner Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <div class="flex flex-col space-y-2">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-lg sm:text-xl font-bold text-gray-900">{{ $statistics['as_owner']['active'] }}</div>
                    </div>
                </div>
                <div class="text-xs sm:text-sm font-medium text-gray-600">Loaned Out</div>
            </div>
        </div>

        <!-- Overdue -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <div class="flex flex-col space-y-2">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-lg sm:text-xl font-bold text-gray-900">{{ $statistics['as_borrower']['overdue'] }}</div>
                    </div>
                </div>
                <div class="text-xs sm:text-sm font-medium text-gray-600">Overdue</div>
            </div>
        </div>

        <!-- Pending Returns -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
            <div class="flex flex-col space-y-2">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19l8-8 3 3-8 8H4v-3z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-lg sm:text-xl font-bold text-gray-900">{{ $statistics['as_owner']['pending_return_confirmation'] }}</div>
                    </div>
                </div>
                <div class="text-xs sm:text-sm font-medium text-gray-600">Pending</div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-2">
            <button 
                wire:click="switchTab('as_borrower')"
                class="@if($activeTab === 'as_borrower') bg-indigo-50 border-b-2 border-indigo-500 text-indigo-600 @else text-gray-500 hover:text-gray-700 bg-gray-50 @endif py-3 px-4 text-center text-sm sm:text-base font-medium transition-colors"
            >
                <div class="flex items-center justify-center space-x-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="hidden sm:inline">Books I've</span>
                    <span>Borrowed</span>
                </div>
            </button>
            <button 
                wire:click="switchTab('as_owner')"
                class="@if($activeTab === 'as_owner') bg-indigo-50 border-b-2 border-indigo-500 text-indigo-600 @else text-gray-500 hover:text-gray-700 bg-gray-50 @endif py-3 px-4 text-center text-sm sm:text-base font-medium transition-colors border-l border-gray-200"
            >
                <div class="flex items-center justify-center space-x-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <span class="hidden sm:inline">Books I've</span>
                    <span>Loaned</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Status Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
        <h3 class="text-sm font-medium text-gray-700 mb-3">Filter by Status</h3>
        <div class="flex flex-wrap gap-2">
            <button 
                wire:click="setStatusFilter('')"
                class="@if($statusFilter === '') bg-indigo-500 text-white @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif inline-flex items-center px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium transition-colors"
            >
                All
            </button>
            @foreach(['delivered', 'received', 'reading', 'returned', 'return_confirmed', 'return_denied', 'lost', 'disputed'] as $status)
                <button 
                    wire:click="setStatusFilter('{{ $status }}')"
                    class="@if($statusFilter === $status) bg-indigo-500 text-white @else bg-gray-100 text-gray-700 hover:bg-gray-200 @endif inline-flex items-center px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium transition-colors"
                >
                    {{ $this->getStatusDisplayName($status) }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Loans List -->
    <div class="space-y-3">
        @if($loans->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No loans found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($activeTab === 'as_borrower')
                        You haven't borrowed any books yet.
                    @else
                        You haven't loaned out any books yet.
                    @endif
                </p>
            </div>
        @else
            @foreach($loans as $loan)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Book Header -->
                    <div class="p-3 sm:p-4">
                        <div class="flex items-start space-x-3">
                            <!-- Book Cover -->
                            <div class="flex-shrink-0">
                                @if($loan->book->image_url)
                                    <img class="h-16 w-12 sm:h-20 sm:w-14 object-cover rounded-lg shadow-sm" src="{{ $loan->book->image_url }}" alt="{{ $loan->book->title }}">
                                @else
                                    <div class="h-16 w-12 sm:h-20 sm:w-14 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg shadow-sm flex items-center justify-center">
                                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Book Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 leading-tight">{{ $loan->book->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">by {{ $loan->book->author }}</p>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $this->getStatusBadgeColor($loan->status) }}-100 text-{{ $this->getStatusBadgeColor($loan->status) }}-800 ml-2 flex-shrink-0">
                                        {{ $this->getStatusDisplayName($loan->status) }}
                                    </span>
                                </div>
                                
                                <!-- Person Info -->
                                <div class="mt-2 text-sm text-gray-600">
                                    @if($activeTab === 'as_borrower')
                                        <span class="inline-flex items-center">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Owner: {{ $loan->owner->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Borrower: {{ $loan->borrower?->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Due Date & Status Info -->
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                                <span class="inline-flex items-center text-gray-600">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Due: {{ \Carbon\Carbon::parse($loan->due_date)->format('M j, Y') }}
                                </span>
                                
                                @if($loan->is_overdue)
                                    <span class="inline-flex items-center text-red-600 font-medium">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $loan->days_overdue }} days overdue
                                    </span>
                                @elseif($loan->is_active && $loan->days_until_due <= 3)
                                    <span class="inline-flex items-center text-yellow-600 font-medium">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Due in {{ $loan->days_until_due }} days
                                    </span>
                                @endif
                            </div>
                            
                            @if($loan->notes)
                                <div class="mt-2 p-2 bg-gray-50 rounded text-sm text-gray-700 italic">
                                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    {{ $loan->notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    @php
                        $availableActions = collect([
                            'mark_received' => ['label' => 'Mark Received', 'color' => 'green'],
                            'mark_reading' => ['label' => 'Start Reading', 'color' => 'blue'],
                            'mark_returned' => ['label' => 'Mark Returned', 'color' => 'yellow'],
                            'confirm_return' => ['label' => 'Confirm Return', 'color' => 'green'],
                            'deny_return' => ['label' => 'Deny Return', 'color' => 'red'],
                            'extend_loan' => ['label' => 'Extend', 'color' => 'indigo'],
                            'mark_lost' => ['label' => 'Mark Lost', 'color' => 'red'],
                            'mark_disputed' => ['label' => 'Dispute', 'color' => 'purple'],
                        ])->filter(function($action, $key) use ($loan) {
                            return $this->canPerformAction($loan, $key);
                        });
                    @endphp
                    
                    @if($availableActions->isNotEmpty())
                        <div class="border-t border-gray-100 bg-gray-50 px-3 py-2 sm:px-4 sm:py-3">
                            @if($availableActions->count() <= 2)
                                <!-- Show buttons directly for 1-2 actions -->
                                <div class="flex space-x-2">
                                    @foreach($availableActions->take(2) as $actionKey => $action)
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, '{{ $actionKey }}')"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-{{ $action['color'] }}-700 bg-{{ $action['color'] }}-100 hover:bg-{{ $action['color'] }}-200 transition-colors"
                                        >
                                            {{ $action['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <!-- Show first action as primary button, rest as overflow menu -->
                                <div class="flex space-x-2">
                                    @php $primaryAction = $availableActions->first(); $primaryKey = $availableActions->keys()->first(); @endphp
                                    <button 
                                        wire:click="openModal({{ $loan->id }}, '{{ $primaryKey }}')"
                                        class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-{{ $primaryAction['color'] }}-700 bg-{{ $primaryAction['color'] }}-100 hover:bg-{{ $primaryAction['color'] }}-200 transition-colors"
                                    >
                                        {{ $primaryAction['label'] }}
                                    </button>
                                    
                                    @if($availableActions->count() > 1)
                                        <div class="relative" x-data="{ open: false }" @if(!function_exists('Alpine')) wire:ignore @endif>
                                            <button 
                                                @if(function_exists('Alpine'))
                                                    @click="open = !open"
                                                @else
                                                    onclick="this.nextElementSibling.classList.toggle('hidden')"
                                                @endif
                                                class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                </svg>
                                            </button>
                                            
                                            <div 
                                                @if(function_exists('Alpine'))
                                                    x-show="open" 
                                                    @click.away="open = false"
                                                @else
                                                    class="hidden"
                                                @endif
                                                class="absolute bottom-full right-0 mb-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10"
                                            >
                                                <div class="py-1">
                                                    @foreach($availableActions->skip(1) as $actionKey => $action)
                                                        <button 
                                                            wire:click="openModal({{ $loan->id }}, '{{ $actionKey }}')"
                                                            @if(function_exists('Alpine'))
                                                                @click="open = false"
                                                            @else
                                                                onclick="this.closest('.relative').querySelector('div:not(.py-1)').classList.add('hidden')"
                                                            @endif
                                                            class="w-full text-left px-4 py-2 text-sm text-{{ $action['color'] }}-700 hover:bg-{{ $action['color'] }}-50 transition-colors"
                                                        >
                                                            {{ $action['label'] }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <!-- Action Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-0">
            <div class="flex items-end sm:items-center justify-center min-h-screen text-center">
                <!-- Backdrop -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 bg-opacity-75"></div>
                </div>

                <!-- Modal -->
                <div class="inline-block w-full sm:w-auto sm:min-w-96 sm:max-w-lg align-bottom sm:align-middle bg-white rounded-t-xl sm:rounded-xl text-left overflow-hidden shadow-xl transform transition-all">
                    <!-- Header -->
                    <div class="bg-white px-4 pt-6 pb-4 sm:p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900">
                                {{ ucwords(str_replace('_', ' ', $actionType)) }}
                            </h3>
                            <button 
                                wire:click="closeModal"
                                class="rounded-full p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="bg-white px-4 py-4 sm:p-6 space-y-4">
                        @if($actionType === 'extend_loan')
                            <div>
                                <label for="extensionDays" class="block text-sm font-medium text-gray-700 mb-2">
                                    Extension Days
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="extensionDays" 
                                    id="extensionDays"
                                    min="1" 
                                    max="30"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base sm:text-sm"
                                >
                            </div>
                        @endif

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes @if(in_array($actionType, ['deny_return', 'mark_lost', 'mark_disputed']))<span class="text-red-500">(Required)</span>@else<span class="text-gray-500">(Optional)</span>@endif
                            </label>
                            <textarea 
                                wire:model="notes" 
                                id="notes"
                                rows="4" 
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base sm:text-sm resize-none"
                                placeholder="Add any additional notes..."
                                @if(in_array($actionType, ['deny_return', 'mark_lost', 'mark_disputed'])) required @endif
                            ></textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 px-4 py-4 sm:px-6 flex flex-col-reverse sm:flex-row sm:justify-end space-y-reverse space-y-3 sm:space-y-0 sm:space-x-3">
                        <button 
                            wire:click="closeModal"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-3 sm:py-2 border border-gray-300 text-base sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            wire:click="performAction"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-3 sm:py-2 border border-gray-300 text-base sm:text-sm font-medium rounded-lg text-white bg-indigo-500 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                        >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
