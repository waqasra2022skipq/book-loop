<div class="relative">
    <!-- Share Button -->
    <button wire:click="openShareModal"
        class="flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">

        <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
            </path>
        </svg>
        Share
    </button>

    <!-- Share Modal -->
    @if ($showShareModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
            wire:click="closeShareModal">
            <div class="bg-white rounded-lg w-full max-w-md" wire:click.stop>
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">Share Post</h3>
                    <button wire:click="closeShareModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4">
                    <!-- Post Preview -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <div class="flex items-center mb-2">
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-xs font-semibold text-gray-700">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="ml-2 text-sm font-medium">{{ $post->user->name }}</span>
                        </div>
                        <p class="text-sm text-gray-700">{{ Str::limit($post->body, 100) }}</p>
                    </div>

                    <!-- Share Options Grid -->
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <!-- Facebook -->
                        <a href="{{ $this->getShareUrl('facebook') }}" target="_blank"
                            class="flex flex-col items-center p-3 rounded-lg border hover:bg-blue-50 hover:border-blue-200 transition-colors duration-200">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Facebook</span>
                        </a>

                        <!-- Twitter -->
                        <a href="{{ $this->getShareUrl('twitter') }}" target="_blank"
                            class="flex flex-col items-center p-3 rounded-lg border hover:bg-sky-50 hover:border-sky-200 transition-colors duration-200">
                            <div class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Twitter</span>
                        </a>

                        <!-- LinkedIn -->
                        <a href="{{ $this->getShareUrl('linkedin') }}" target="_blank"
                            class="flex flex-col items-center p-3 rounded-lg border hover:bg-blue-50 hover:border-blue-200 transition-colors duration-200">
                            <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">LinkedIn</span>
                        </a>

                        <!-- WhatsApp -->
                        <a href="{{ $this->getShareUrl('whatsapp') }}" target="_blank"
                            class="flex flex-col items-center p-3 rounded-lg border hover:bg-green-50 hover:border-green-200 transition-colors duration-200">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">WhatsApp</span>
                        </a>

                        <!-- Email -->
                        <a href="{{ $this->getShareUrl('email') }}"
                            class="flex flex-col items-center p-3 rounded-lg border hover:bg-gray-50 hover:border-gray-200 transition-colors duration-200">
                            <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Email</span>
                        </a>

                        <!-- Copy Link -->
                        <button wire:click="copyToClipboard"
                            class="flex flex-col items-center p-3 rounded-lg border hover:bg-gray-50 hover:border-gray-200 transition-colors duration-200">
                            <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Copy Link</span>
                        </button>
                    </div> <!-- Direct Link -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Direct Link</label>
                        <div class="flex items-center space-x-2">
                            <input type="text" readonly value="{{ $this->postUrl }}"
                                class="flex-1 px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg text-gray-600"
                                id="post-url-{{ $post->id }}">
                            <button onclick="copyToClipboard('post-url-{{ $post->id }}')"
                                class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for copy-to-clipboard event
        Livewire.on('copy-to-clipboard', (data) => {
            copyToClipboard(null, data[0].url);
        });
    });

    function copyToClipboard(inputId, url = null) {
        let textToCopy;

        if (url) {
            textToCopy = url;
            // Create a temporary textarea to copy the URL
            const textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        } else if (inputId) {
            const input = document.getElementById(inputId);
            input.select();
            document.execCommand('copy');
        }

        // Show success feedback
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Link copied to clipboard!';
        document.body.appendChild(toast);

        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    }
</script>
