@props([
    'value' => '',
    'placeholder' => 'Click to add...',
    'route' => '',
    'field' => '',
    'maxLength' => 1000,
    'type' => 'text', // text, textarea
    'file' => null, // File object for preview modal
    'class' => '',
])

<div 
    x-data="editableCell(@js($value), @js($route), @js($field), @js($file))"
    class="relative group max-w-full {{ $class }}"
>
    <!-- Display Mode -->
    <div 
        x-show="!editing" 
        class="min-h-[2rem] flex items-center rounded px-2 py-1 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors overflow-hidden"
        :class="{ 'text-gray-500 dark:text-gray-400 italic': !value }"
    >
        <span 
            @click="startEdit()"
            x-text="(value && value.length > 50) ? value.substring(0, 50) + '...' : (value || '{{ $placeholder }}')"
            :title="value && value.length > 50 && value.length <= 150 ? value : null"
            class="cursor-pointer truncate block"
        ></span>
        
        <!-- Show indicators for truncated notes -->
        <div x-show="value && value.length > 50" class="flex items-center ml-2 gap-2 flex-shrink-0">
            <!-- View button for all truncated notes -->
            <button 
                @click.stop="viewFileWithNotes()"
                class="flex items-center text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 px-2 py-1 rounded transition-colors"
                title="View full note"
            >
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span x-show="value && value.length <= 150">+<span x-text="value.length - 50"></span></span>
                <span x-show="value && value.length > 150">View</span>
            </button>
        </div>
        
        <!-- Edit Icon -->
        <svg class="w-4 h-4 ml-2 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
    </div>
    
    <!-- Edit Mode -->
    <div x-show="editing" class="relative" x-cloak>
        @if($type === 'textarea')
            <textarea
                x-ref="input"
                x-model="value"
                @keydown.escape="cancelEdit()"
                @keydown.ctrl.enter="saveEdit()"
                maxlength="{{ $maxLength }}"
                rows="3"
                class="w-full px-2 py-1 text-sm border border-indigo-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 resize-none"
                placeholder="{{ $placeholder }}"
                x-bind:disabled="saving"
            ></textarea>
        @else
            <input
                x-ref="input"
                x-model="value"
                @keydown.escape="cancelEdit()"
                @keydown.enter="saveEdit()"
                type="text"
                maxlength="{{ $maxLength }}"
                class="w-full px-2 py-1 text-sm border border-indigo-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                placeholder="{{ $placeholder }}"
                x-bind:disabled="saving"
            />
        @endif
        
        <!-- Loading Spinner -->
        <div x-show="saving" class="absolute right-2 top-1/2 transform -translate-y-1/2">
            <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center gap-2">
                <x-ui.button.primary 
                    size="sm" 
                    @click="saveEdit()"
                    x-bind:disabled="saving"
                    class="text-xs"
                >
                    <svg x-show="!saving" class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg x-show="saving" class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="saving ? 'Saving...' : 'Save'"></span>
                </x-ui.button.primary>
                
                <x-ui.button.secondary 
                    size="sm" 
                    @click="cancelEdit()"
                    x-bind:disabled="saving"
                    class="text-xs"
                >
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </x-ui.button.secondary>
            </div>
            
            <!-- Help Text -->
            <div class="text-xs text-gray-400">
                @if($type === 'textarea')
                    Ctrl+Enter to save
                @else
                    Enter to save
                @endif
            </div>
        </div>
    </div>
    
    <!-- Error Message -->
    <div x-show="error" x-text="error" class="text-xs text-red-500 mt-1" x-cloak></div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('editableCell', (initialValue, route, field, file) => ({
                    editing: false,
                    value: initialValue || '',
                    originalValue: initialValue || '',
                    saving: false,
                    error: null,
                    file: file,
                    
                    startEdit() {
                        this.editing = true;
                        this.originalValue = this.value;
                        this.$nextTick(() => {
                            this.$refs.input.focus();
                            if (this.$refs.input.select) this.$refs.input.select();
                        });
                    },
                    
                    cancelEdit() {
                        this.value = this.originalValue;
                        this.editing = false;
                        this.error = null;
                    },
                    
                    async saveEdit() {
                        if (this.value === this.originalValue) {
                            this.editing = false;
                            return;
                        }
                        
                        this.saving = true;
                        this.error = null;
                        
                        try {
                            const response = await fetch(route, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    [field]: this.value
                                })
                            });
                            
                            const data = await response.json();
                            
                            if (response.ok && data.success) {
                                this.originalValue = this.value;
                                this.editing = false;
                                
                                // Show success feedback
                                if (typeof toastr !== 'undefined') {
                                    toastr.success(data.message || 'Updated successfully');
                                }
                            } else {
                                throw new Error(data.message || 'Update failed');
                            }
                        } catch (err) {
                            this.error = err.message;
                            if (typeof toastr !== 'undefined') {
                                toastr.error(this.error);
                            }
                        } finally {
                            this.saving = false;
                        }
                    },
                    
                    viewFileWithNotes() {
                        if (this.file) {
                            // Check if file is previewable
                            const previewableTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'];
                            if (previewableTypes.includes(this.file.mime_type)) {
                                // Dispatch file preview with current notes
                                this.$dispatch('file-preview-data', {
                                    name: this.file.original_name,
                                    type: this.file.mime_type,
                                    previewUrl: this.file.preview_url,
                                    downloadUrl: this.file.download_url,
                                    notes: this.value
                                });
                                this.$dispatch('open-modal', 'file-preview');
                            } else {
                                // For non-previewable files, just show notes in a simple modal
                                alert('Notes: ' + (this.value || 'No notes for this file'));
                            }
                        }
                    }
                }));
            });
        </script>
    @endpush
@endonce