@props(['folder'])

<nav class="relative w-full overflow-hidden" aria-label="Breadcrumb">
    <!-- Responsive breadcrumb with horizontal scrolling -->
    <div class="overflow-x-auto pb-1 hide-scrollbar">
        <ol role="list" class="flex items-center whitespace-nowrap min-w-full">
            <!-- Home link - always visible -->
            <li class="flex-shrink-0">
                <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                    Home
                </a>
            </li>
            <li class="flex-shrink-0 mx-1 text-gray-400">/</li>
            <li class="flex-shrink-0">
                <a href="{{ route('user.folders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                    Folders
                </a>
            </li>
            
            @php
                $ancestors = collect([]);
                $current = $folder;
                while($current->parent) {
                    $ancestors->prepend($current->parent);
                    $current = $current->parent;
                }
                
                // On small screens, if we have many ancestors, we'll show a limited number
                $totalAncestors = $ancestors->count();
                $displayAll = true;
            @endphp

            @foreach($ancestors as $index => $ancestor)
                <li class="flex-shrink-0 mx-1 text-gray-400">/</li>
                <li class="flex-shrink-0">
                    <a href="{{ route('user.folders.show', $ancestor) }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300 max-w-[120px] md:max-w-none truncate inline-block align-bottom">
                        {{ $ancestor->name }}
                    </a>
                </li>
            @endforeach

            <!-- Current folder - always visible -->
            <li class="flex-shrink-0 mx-1 text-gray-400">/</li>
            <li class="flex-shrink-0">
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100 max-w-[150px] md:max-w-none truncate inline-block align-bottom">{{ $folder->name }}</span>
            </li>
        </ol>
    </div>
    
    <!-- Gradient indicators for scrolling -->
    <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent dark:from-gray-900 pointer-events-none hidden md:hidden" id="left-fade"></div>
    <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent dark:from-gray-900 pointer-events-none hidden md:hidden" id="right-fade"></div>
</nav>

<style>
    /* Hide scrollbar but keep functionality */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    // Add scroll indicators
    document.addEventListener('DOMContentLoaded', function() {
        const scrollContainer = document.querySelector('.overflow-x-auto');
        const leftFade = document.getElementById('left-fade');
        const rightFade = document.getElementById('right-fade');
        
        if (scrollContainer && leftFade && rightFade) {
            // Check if scrolling is needed
            function checkScroll() {
                if (scrollContainer.scrollWidth > scrollContainer.clientWidth) {
                    // Show right fade if not scrolled all the way right
                    rightFade.classList.toggle('hidden', 
                        scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth);
                    
                    // Show left fade if scrolled right
                    leftFade.classList.toggle('hidden', scrollContainer.scrollLeft <= 0);
                } else {
                    // Hide both if no scrolling needed
                    leftFade.classList.add('hidden');
                    rightFade.classList.add('hidden');
                }
            }
            
            // Initial check
            checkScroll();
            
            // Check on scroll
            scrollContainer.addEventListener('scroll', checkScroll);
            
            // Check on resize
            window.addEventListener('resize', checkScroll);
        }
    });
</script>