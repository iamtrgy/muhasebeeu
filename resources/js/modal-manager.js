/**
 * Modal Manager - Centralized modal management system
 * Handles modal stacking, body overflow, z-index management, and event cleanup
 */
class ModalManager {
    constructor() {
        this.activeModals = [];
        this.baseZIndex = 50;
        this.zIndexIncrement = 10;
        this.bodyOverflowCounter = 0;
        this.escapeListener = null;
        
        this.init();
    }

    init() {
        // Setup global escape key listener
        this.escapeListener = (event) => {
            if (event.key === 'Escape' && this.activeModals.length > 0) {
                event.preventDefault();
                event.stopPropagation();
                this.closeTopModal();
            }
        };
        
        document.addEventListener('keydown', this.escapeListener);
        
        // Setup Alpine.js integration
        if (window.Alpine) {
            this.setupAlpineIntegration();
        }
    }

    setupAlpineIntegration() {
        // Add modal manager to Alpine global properties
        window.Alpine.magic('modalManager', () => this);
        
        // Listen for Alpine modal events
        document.addEventListener('alpine:init', () => {
            window.Alpine.data('managedModal', (config = {}) => ({
                modalId: config.id || `modal-${Date.now()}`,
                show: false,
                
                init() {
                    // Register modal when initialized
                    this.$watch('show', (value) => {
                        if (value) {
                            window.modalManager.open(this.modalId, this.$el);
                        } else {
                            window.modalManager.close(this.modalId);
                        }
                    });
                },
                
                openModal() {
                    this.show = true;
                },
                
                closeModal() {
                    this.show = false;
                }
            }));
        });
    }

    /**
     * Open a modal
     */
    open(modalId, modalElement) {
        // Check if modal is already open
        if (this.activeModals.find(m => m.id === modalId)) {
            console.warn(`Modal ${modalId} is already open`);
            return;
        }

        // Calculate z-index for new modal
        const zIndex = this.baseZIndex + (this.activeModals.length * this.zIndexIncrement);
        
        // Add to active modals
        this.activeModals.push({
            id: modalId,
            element: modalElement,
            zIndex: zIndex,
            previousFocus: document.activeElement
        });

        // Apply z-index to modal
        if (modalElement) {
            modalElement.style.zIndex = zIndex;
            
            // Also update backdrop z-index if it exists
            const backdrop = modalElement.querySelector('.fixed.inset-0');
            if (backdrop && backdrop !== modalElement) {
                backdrop.style.zIndex = zIndex - 1;
            }
        }

        // Handle body overflow
        this.lockBodyScroll();

        // Emit event
        this.emit('modal:opened', { modalId, zIndex });
    }

    /**
     * Close a specific modal
     */
    close(modalId) {
        const modalIndex = this.activeModals.findIndex(m => m.id === modalId);
        
        if (modalIndex === -1) {
            return;
        }

        const modal = this.activeModals[modalIndex];
        
        // Remove from active modals
        this.activeModals.splice(modalIndex, 1);

        // Restore focus
        if (modal.previousFocus && modal.previousFocus.focus) {
            modal.previousFocus.focus();
        }

        // Handle body overflow
        this.unlockBodyScroll();

        // Recalculate z-indexes for remaining modals
        this.recalculateZIndexes();

        // Emit event
        this.emit('modal:closed', { modalId });
    }

    /**
     * Close the topmost modal
     */
    closeTopModal() {
        if (this.activeModals.length === 0) {
            return;
        }

        const topModal = this.activeModals[this.activeModals.length - 1];
        
        // Try to close via Alpine.js first
        if (topModal.element && window.Alpine) {
            const alpineData = window.Alpine.$data(topModal.element);
            if (alpineData && alpineData.show !== undefined) {
                alpineData.show = false;
                return;
            }
        }

        // Otherwise close directly
        this.close(topModal.id);
    }

    /**
     * Close all modals
     */
    closeAll() {
        // Close from top to bottom
        while (this.activeModals.length > 0) {
            this.closeTopModal();
        }
    }

    /**
     * Lock body scroll
     */
    lockBodyScroll() {
        this.bodyOverflowCounter++;
        
        if (this.bodyOverflowCounter === 1) {
            // Store original body styles
            this.originalBodyStyles = {
                overflow: document.body.style.overflow,
                paddingRight: document.body.style.paddingRight
            };

            // Calculate scrollbar width
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            
            // Apply styles
            document.body.style.overflow = 'hidden';
            if (scrollbarWidth > 0) {
                document.body.style.paddingRight = `${scrollbarWidth}px`;
            }
            
            // Also add class for CSS-based styling
            document.body.classList.add('modal-open');
        }
    }

    /**
     * Unlock body scroll
     */
    unlockBodyScroll() {
        this.bodyOverflowCounter--;
        
        if (this.bodyOverflowCounter === 0) {
            // Restore original styles
            document.body.style.overflow = this.originalBodyStyles.overflow || '';
            document.body.style.paddingRight = this.originalBodyStyles.paddingRight || '';
            
            // Remove class
            document.body.classList.remove('modal-open');
        }
        
        // Ensure counter doesn't go negative
        if (this.bodyOverflowCounter < 0) {
            this.bodyOverflowCounter = 0;
        }
    }

    /**
     * Recalculate z-indexes for all active modals
     */
    recalculateZIndexes() {
        this.activeModals.forEach((modal, index) => {
            const zIndex = this.baseZIndex + (index * this.zIndexIncrement);
            modal.zIndex = zIndex;
            
            if (modal.element) {
                modal.element.style.zIndex = zIndex;
                
                // Update backdrop z-index
                const backdrop = modal.element.querySelector('.fixed.inset-0');
                if (backdrop && backdrop !== modal.element) {
                    backdrop.style.zIndex = zIndex - 1;
                }
            }
        });
    }

    /**
     * Get currently active modal
     */
    getActiveModal() {
        return this.activeModals[this.activeModals.length - 1] || null;
    }

    /**
     * Check if a modal is open
     */
    isOpen(modalId) {
        return this.activeModals.some(m => m.id === modalId);
    }

    /**
     * Get modal stack info
     */
    getStack() {
        return this.activeModals.map(m => ({
            id: m.id,
            zIndex: m.zIndex
        }));
    }

    /**
     * Emit custom events
     */
    emit(eventName, detail) {
        document.dispatchEvent(new CustomEvent(eventName, { detail }));
    }

    /**
     * Cleanup
     */
    destroy() {
        if (this.escapeListener) {
            document.removeEventListener('keydown', this.escapeListener);
        }
        
        this.closeAll();
    }
}

// Initialize modal manager globally
window.modalManager = new ModalManager();

// Export for ES6 modules
export default window.modalManager;