@props([
    'name' => null,           // For Alpine event-based opening
    'id' => null,             // For direct DOM/function-based opening  
    'show' => false,          // Initial state
    'maxWidth' => '2xl',      // Size variants: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
    'closeButton' => false,   // Show close button in top-right
    'focusable' => true,      // Enable focus management
    'closeable' => true,      // Allow closing via backdrop/ESC
])

@php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
    '7xl' => 'sm:max-w-7xl',
][$maxWidth] ?? 'sm:max-w-2xl';

// Use either name or id as identifier
$modalId = $id ?? $name ?? 'modal-' . uniqid();
@endphp

<div
    x-data="{ 
        show: {{ $show ? 'true' : 'false' }},
        modalId: '{{ $modalId }}',
        init() {
            // Listen for open events
            document.addEventListener('open-modal', (event) => {
                if (event.detail === this.modalId) {
                    this.open();
                }
            });
            
            // Listen for close events
            document.addEventListener('close-modal', (event) => {
                if (!event.detail || event.detail === this.modalId) {
                    this.close();
                }
            });
        },
        open() {
            this.show = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.show = false;
            document.body.style.overflow = '';
        }
    }"
    x-show="show"
    x-on:keydown.escape.window="{{ $closeable ? 'close()' : '' }}"
    @if($id)
        id="{{ $id }}"
    @endif
    class="relative z-50"
    aria-labelledby="{{ $modalId }}-title"
    aria-modal="true"
    role="dialog"
>
    <!-- Backdrop -->
    <div 
        x-show="show" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
        @if($closeable)
            @click="close()"
        @endif
    ></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.stop
                class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full {{ $maxWidthClass }}"
            >
                @if($closeButton)
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button
                            @click="close()"
                            type="button"
                            class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            // Global helper functions for non-Alpine contexts
            if (typeof window.openModal !== 'function') {
                window.openModal = function(modalId) {
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: modalId }));
                };
            }
            
            if (typeof window.closeModal !== 'function') {
                window.closeModal = function(modalId) {
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: modalId }));
                };
            }
        </script>
    @endpush
@endonce