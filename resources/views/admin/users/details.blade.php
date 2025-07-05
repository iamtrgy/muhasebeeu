<x-admin.layout 
    title="{{ $user->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Users'), 'href' => route('admin.users.index')],
        ['title' => $user->name]
    ]"
>
    <div class="space-y-6">
        @include('admin.users.partials.profile-header', ['user' => $user])

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left column for user info -->
            <div class="lg:col-span-1">
                @include('admin.users.partials.user-info', ['user' => $user])
            </div>
            
            <!-- Right column for tabs -->
            <div class="lg:col-span-2">
                @include('admin.users.partials.tabs', ['user' => $user])
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get current tab from URL
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            
            // If tab parameter exists, set active tab
            if (tabParam) {
                window.dispatchEvent(new CustomEvent('set-active-tab', {
                    detail: { tab: tabParam }
                }));
            }
            
            // When tab changes, update URL without page reload
            window.addEventListener('tab-changed', function(e) {
                const tab = e.detail.tab;
                const url = new URL(window.location);
                url.searchParams.set('tab', tab);
                window.history.pushState({}, '', url);
            });
            
            // Fix all folder links to preserve tab parameter
            document.querySelectorAll('a[href*="folder_id"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get the active tab
                    const activeTab = document.querySelector('.border-blue-500').textContent.trim().toLowerCase();
                    const tabName = activeTab.replace(/\s+/g, '').toLowerCase();
                    
                    // Create URL with tab parameter
                    const url = new URL(this.href);
                    url.searchParams.set('tab', 'documents');
                    window.location.href = url.toString();
                });
            });
        });
    </script>
    @endpush
</x-admin.layout>