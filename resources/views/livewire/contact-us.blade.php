<section class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 flex items-center justify-center py-12">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-2xl border border-blue-100 p-8 sm:p-12">
        <h1 class="text-3xl font-extrabold text-blue-700 mb-2">Contact Us</h1>
        <p class="text-gray-600 mb-6">We value your feedback, suggestions, and questions. Please fill out the form below and we'll get back to you as soon as possible.</p>
        @if (session()->has('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        <form wire:submit.prevent="submit" class="space-y-6">
            <div>
                <flux:input wire:model="name" label="Your Name" required autocomplete="name" />
            </div>
            <div>
                <flux:input wire:model="email" label="Your Email" type="email" required autocomplete="email" />
            </div>
            <div>
                <flux:input wire:model="subject" label="Subject (optional)" type="text" autocomplete="subject" />
            </div>
            <div>
                <flux:radio.group wire:model="type" label="Type">
                    <flux:radio value="query" label="General Query" />
                    <flux:radio value="suggestion" label="Suggestion" />
                    <flux:radio value="comment" label="Comment" />
                </flux:radio.group>
            </div>
            <div>
                <flux:textarea wire:model="message" label="Message" rows="5" required placeholder="Type your message here..." />
            </div>
            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">Send Message</flux:button>
            </div>
        </form>
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>We aim to respond to all queries within 24 hours.</p>
            <p>For urgent matters, please email <a href="mailto:contact@loopyourbook.com" class="text-blue-600 underline">contact@loopyourbook.com</a></p>
        </div>
    </div>
</section>
