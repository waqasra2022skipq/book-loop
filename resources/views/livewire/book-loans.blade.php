<section class="p-4 space-y-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- As Borrower Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Books Borrowed</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['as_borrower']['active'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- As Owner Stats -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Books Loaned Out</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['as_owner']['active'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Overdue</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['as_borrower']['overdue'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Returns -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19l8-8 3 3-8 8H4v-3z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Returns</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['as_owner']['pending_return_confirmation'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button 
                    wire:click="switchTab('as_borrower')"
                    class="@if($activeTab === 'as_borrower') border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                >
                    Books I've Borrowed
                </button>
                <button 
                    wire:click="switchTab('as_owner')"
                    class="@if($activeTab === 'as_owner') border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                >
                    Books I've Loaned Out
                </button>
            </nav>
        </div>
    </div>

    <!-- Status Filters -->
    <div class="mt-4 flex flex-wrap gap-2">
        <button 
            wire:click="setStatusFilter('')"
            class="@if($statusFilter === '') bg-indigo-100 text-indigo-800 @else bg-gray-100 text-gray-800 hover:bg-gray-200 @endif inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
        >
            All
        </button>
        @foreach(['delivered', 'received', 'reading', 'returned', 'return_confirmed', 'return_denied', 'lost', 'disputed'] as $status)
            <button 
                wire:click="setStatusFilter('{{ $status }}')"
                class="@if($statusFilter === $status) bg-indigo-100 text-indigo-800 @else bg-gray-100 text-gray-800 hover:bg-gray-200 @endif inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            >
                {{ $this->getStatusDisplayName($status) }}
            </button>
        @endforeach
    </div>

    <!-- Loans List -->
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
        @if($loans->isEmpty())
            <div class="text-center py-12">
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
            <ul role="list" class="divide-y divide-gray-200">
                @foreach($loans as $loan)
                    <li class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($loan->book->image_url)
                                            <img class="h-12 w-8 object-cover rounded" src="{{ $loan->book->image_url }}" alt="{{ $loan->book->title }}">
                                        @else
                                            <div class="h-12 w-8 bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900 truncate">{{ $loan->book->title }}</h3>
                                        <p class="text-sm text-gray-500">by {{ $loan->book->author }}</p>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            @if($activeTab === 'as_borrower')
                                                <span>Owner: {{ $loan->owner->name }}</span>
                                            @else
                                                <span>Borrower: {{ $loan->borrower->name }}</span>
                                            @endif
                                            <span class="mx-2">•</span>
                                            <span>Due: {{ \Carbon\Carbon::parse($loan->due_date)->format('M j, Y') }}</span>
                                            @if($loan->is_overdue)
                                                <span class="mx-2 text-red-600 font-medium">• {{ $loan->days_overdue }} days overdue</span>
                                            @elseif($loan->is_active && $loan->days_until_due <= 3)
                                                <span class="mx-2 text-yellow-600 font-medium">• Due in {{ $loan->days_until_due }} days</span>
                                            @endif
                                        </div>
                                        @if($loan->notes)
                                            <p class="mt-1 text-sm text-gray-600 italic">{{ $loan->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $this->getStatusBadgeColor($loan->status) }}-100 text-{{ $this->getStatusBadgeColor($loan->status) }}-800">
                                    {{ $this->getStatusDisplayName($loan->status) }}
                                </span>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    @if($this->canPerformAction($loan, 'mark_received'))
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, 'mark_received')"
                                            class="text-green-600 hover:text-green-900 text-sm font-medium"
                                        >
                                            Mark Received
                                        </button>
                                    @endif

                                    @if($this->canPerformAction($loan, 'mark_reading'))
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, 'mark_reading')"
                                            class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                        >
                                            Start Reading
                                        </button>
                                    @endif

                                    @if($this->canPerformAction($loan, 'mark_returned'))
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, 'mark_returned')"
                                            class="text-yellow-600 hover:text-yellow-900 text-sm font-medium"
                                        >
                                            Mark Returned
                                        </button>
                                    @endif

                                    @if($this->canPerformAction($loan, 'confirm_return'))
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, 'confirm_return')"
                                            class="text-green-600 hover:text-green-900 text-sm font-medium"
                                        >
                                            Confirm Return
                                        </button>
                                    @endif

                                    @if($this->canPerformAction($loan, 'deny_return'))
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, 'deny_return')"
                                            class="text-red-600 hover:text-red-900 text-sm font-medium"
                                        >
                                            Deny Return
                                        </button>
                                    @endif

                                    @if($this->canPerformAction($loan, 'extend_loan'))
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, 'extend_loan')"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                        >
                                            Extend
                                        </button>
                                    @endif

                                    @if($this->canPerformAction($loan, 'mark_lost'))
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, 'mark_lost')"
                                            class="text-red-600 hover:text-red-900 text-sm font-medium"
                                        >
                                            Mark Lost
                                        </button>
                                    @endif

                                    @if($this->canPerformAction($loan, 'mark_disputed'))
                                        <button 
                                            wire:click="openModal({{ $loan->id }}, 'mark_disputed')"
                                            class="text-purple-600 hover:text-purple-900 text-sm font-medium"
                                        >
                                            Dispute
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Action Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ ucwords(str_replace('_', ' ', $actionType)) }}
                        </h3>

                        @if($actionType === 'extend_loan')
                            <div class="mb-4">
                                <label for="extensionDays" class="block text-sm font-medium text-gray-700">
                                    Extension Days
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="extensionDays" 
                                    id="extensionDays"
                                    min="1" 
                                    max="30"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                Notes @if(in_array($actionType, ['deny_return', 'mark_lost', 'mark_disputed']))(Required)@else(Optional)@endif
                            </label>
                            <textarea 
                                wire:model="notes" 
                                id="notes"
                                rows="3" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Add any additional notes..."
                                @if(in_array($actionType, ['deny_return', 'mark_lost', 'mark_disputed'])) required @endif
                            ></textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            wire:click="performAction"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Confirm
                        </button>
                        <button 
                            wire:click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
