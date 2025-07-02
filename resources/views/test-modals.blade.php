@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Modal Stack Testing</h1>
                
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Test Modal Stacking</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Click the buttons below to test modal stacking functionality.</p>
                        
                        <div class="flex flex-wrap gap-4">
                            <button onclick="Livewire.dispatch('openModal', { component: 'modal1' })" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Open Modal 1
                            </button>
                            
                            <button onclick="window.openModal('test-modal-2')" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Open Modal 2
                            </button>
                            
                            <button onclick="window.previewFile('test.pdf', 'application/pdf', '#')" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Open File Preview
                            </button>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Modal Manager Status</h2>
                        <div id="modal-status" class="text-sm text-gray-600 dark:text-gray-400">
                            <p>Active Modals: <span id="active-count">0</span></p>
                            <p>Body Overflow Counter: <span id="overflow-count">0</span></p>
                            <div id="modal-stack" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Modal 1 -->
<x-modal name="modal1" :show="false">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Modal 1</h2>
        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            This is the first modal. You can open another modal from here.
        </p>
        
        <div class="mt-6 flex gap-4">
            <button onclick="Livewire.dispatch('openModal', { component: 'modal2' })" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                Open Modal 2
            </button>
            
            <button onclick="Livewire.dispatch('closeModal', { component: 'modal1' })" 
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Close
            </button>
        </div>
    </div>
</x-modal>

<!-- Test Modal 2 -->
<x-ui.modal id="test-modal-2" maxWidth="lg">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Modal 2</h2>
        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            This is the second modal. It should stack properly on top of Modal 1.
        </p>
        
        <div class="mt-6 flex gap-4">
            <button onclick="window.previewFile('example.jpg', 'image/jpeg', '/storage/example.jpg')" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Open File Preview
            </button>
            
            <button onclick="window.closeModal('test-modal-2')" 
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Close
            </button>
        </div>
    </div>
</x-ui.modal>

<!-- Test Modal 3 (nested in modal 2) -->
<x-modal name="modal2" :show="false" maxWidth="md">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Modal 2 (Nested)</h2>
        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            This modal was opened from Modal 1. Press ESC to close only this modal.
        </p>
        
        <div class="mt-6">
            <button onclick="Livewire.dispatch('closeModal', { component: 'modal2' })" 
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Close This Modal
            </button>
        </div>
    </div>
</x-modal>

@push('scripts')
<script>
// Monitor modal manager status
document.addEventListener('DOMContentLoaded', function() {
    function updateModalStatus() {
        if (window.modalManager) {
            document.getElementById('active-count').textContent = window.modalManager.activeModals.length;
            document.getElementById('overflow-count').textContent = window.modalManager.bodyOverflowCounter;
            
            const stack = window.modalManager.getStack();
            const stackHtml = stack.map(m => `<div>- ${m.id} (z-index: ${m.zIndex})</div>`).join('');
            document.getElementById('modal-stack').innerHTML = stackHtml || '<div>No active modals</div>';
        }
    }
    
    // Update status on modal events
    document.addEventListener('modal:opened', updateModalStatus);
    document.addEventListener('modal:closed', updateModalStatus);
    
    // Initial update
    updateModalStatus();
    
    // Update every second for real-time monitoring
    setInterval(updateModalStatus, 1000);
});
</script>
@endpush
@endsection