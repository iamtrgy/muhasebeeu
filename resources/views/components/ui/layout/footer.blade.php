@props([
    'sticky' => false,
])

<footer @class([
    'bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800',
    'sticky bottom-0 z-20' => $sticky,
])>
    <div class="px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
            <!-- Left Section - Copyright -->
            <div class="flex items-center space-x-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                </p>
            </div>

            <!-- Center Section (Optional) -->
            @if(isset($center))
                <div class="hidden md:block">
                    {{ $center }}
                </div>
            @endif

            <!-- Right Section - Links -->
            <div class="flex items-center space-x-6">
                @if(isset($links))
                    {{ $links }}
                @else
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Privacy Policy
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Terms of Service
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Support
                    </a>
                @endif
            </div>
        </div>

        <!-- Optional Additional Content -->
        @if(isset($content))
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                {{ $content }}
            </div>
        @endif
    </div>
</footer>