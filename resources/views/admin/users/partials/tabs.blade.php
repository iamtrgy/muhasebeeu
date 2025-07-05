<!-- Tabs -->
<x-ui.tabs.base defaultTab="{{ request()->query('tab', 'details') }}">
    <x-ui.tabs.list>
        <x-ui.tabs.tab name="details">{{ __('Account Details') }}</x-ui.tabs.tab>
        <x-ui.tabs.tab name="companies">{{ __('Companies') }}</x-ui.tabs.tab>
        <x-ui.tabs.tab name="documents">{{ __('Documents') }}</x-ui.tabs.tab>
        <x-ui.tabs.tab name="activity">{{ __('Activity') }}</x-ui.tabs.tab>
    </x-ui.tabs.list>
    
    <x-ui.tabs.panels>
        <x-ui.tabs.panel name="details">
            @include('admin.users.partials.tab-details')
        </x-ui.tabs.panel>
        
        <x-ui.tabs.panel name="companies">
            @include('admin.users.partials.tab-companies')
        </x-ui.tabs.panel>
        
        <x-ui.tabs.panel name="documents">
            @include('admin.users.partials.tab-documents')
        </x-ui.tabs.panel>
        
        <x-ui.tabs.panel name="activity">
            @include('admin.users.partials.tab-activity')
        </x-ui.tabs.panel>
    </x-ui.tabs.panels>
</x-ui.tabs.base>
