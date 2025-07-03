@props([
    'defaultTab' => null,
    'variant' => 'underline', // underline, pills, bordered
])

<div 
    x-data="{ 
        activeTab: '{{ $defaultTab }}',
        init() {
            if (!this.activeTab) {
                const firstTab = this.$el.querySelector('[role=tab]');
                if (firstTab) {
                    this.activeTab = firstTab.getAttribute('data-tab');
                }
            }
        }
    }" 
    {{ $attributes }}
>
    {{ $slot }}
</div>