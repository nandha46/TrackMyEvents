import './bootstrap';
import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

// Setup Alpine globally
window.Alpine = Alpine;
window.flatpickr = flatpickr;

// Dark mode helper
window.toggleDarkMode = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: isDark } }));
};

// Flatpickr Alpine Directive
Alpine.directive('datepicker', (el, { expression }, { evaluateLater, effect }) => {
    flatpickr(el, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'F j, Y',
        animate: true,
        monthSelectorType: 'static',
        allowInput: true,
    });
});

Alpine.directive('timepicker', (el) => {
    flatpickr(el, {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        altInput: true,
        altFormat: 'h:i K',
        time_24hr: false,
        allowInput: true,
    });
});

// Countdown Timer Alpine Component
Alpine.data('countdown', (targetDateStr, targetTimeStr = '00:00:00', isPastInitial = false) => ({
    targetDateTime: null,
    isPast: isPastInitial,
    years: 0,
    months: 0,
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
    totalDays: 0,
    timer: null,

    init() {
        this.parseTarget();
        this.calculate();
        this.timer = setInterval(() => this.calculate(), 1000);
    },

    destroy() {
        if (this.timer) clearInterval(this.timer);
    },

    parseTarget() {
        let cleanTime = targetTimeStr ? targetTimeStr.trim() : '00:00:00';
        if (cleanTime.length === 5) cleanTime += ':00';
        const isoString = `${targetDateStr}T${cleanTime}`;
        this.targetDateTime = new Date(isoString);
        if (isNaN(this.targetDateTime.getTime())) {
            this.targetDateTime = new Date(targetDateStr);
        }
    },

    calculate() {
        const now = new Date();
        const diffMs = this.targetDateTime.getTime() - now.getTime();
        this.isPast = diffMs < 0;

        const absDiffSec = Math.floor(Math.abs(diffMs) / 1000);
        
        this.seconds = absDiffSec % 60;
        const totalMinutes = Math.floor(absDiffSec / 60);
        this.minutes = totalMinutes % 60;
        const totalHours = Math.floor(totalMinutes / 60);
        this.hours = totalHours % 24;
        const totalDays = Math.floor(totalHours / 24);
        this.totalDays = totalDays;

        this.years = Math.floor(totalDays / 365);
        const remainingDaysAfterYears = totalDays % 365;
        this.months = Math.floor(remainingDaysAfterYears / 30);
        this.days = remainingDaysAfterYears % 30;
    }
}));

// Toast Notifications System
Alpine.data('toastSystem', () => ({
    toasts: [],
    init() {
        window.addEventListener('toast', (e) => {
            this.addToast(e.detail.message, e.detail.type || 'success', e.detail.duration || 4000);
        });

        // Flash message from meta tags
        const flashSuccess = document.querySelector('meta[name="flash-success"]')?.content;
        const flashError = document.querySelector('meta[name="flash-error"]')?.content;
        const flashStatus = document.querySelector('meta[name="flash-status"]')?.content;

        if (flashSuccess) this.addToast(flashSuccess, 'success');
        if (flashError) this.addToast(flashError, 'error');
        if (flashStatus) this.addToast(flashStatus, 'info');
    },

    addToast(message, type = 'success', duration = 4000) {
        if (!message) return;
        const id = Date.now() + Math.random().toString(36).substr(2, 9);
        this.toasts.push({ id, message, type });

        setTimeout(() => {
            this.removeToast(id);
        }, duration);
    },

    removeToast(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}));

// Delete Confirmation Modal System
Alpine.data('confirmModal', () => ({
    open: false,
    title: 'Delete Confirmation',
    message: 'Are you sure you want to delete this event? This action cannot be undone.',
    formAction: '',
    method: 'POST',

    showModal(actionUrl, title = null, message = null) {
        this.formAction = actionUrl;
        if (title) this.title = title;
        if (message) this.message = message;
        this.open = true;
    },

    closeModal() {
        this.open = false;
    }
}));

// Batch Management System
Alpine.data('batchManager', () => ({
    selectedIds: [],
    selectAll: false,

    toggleSelectAll(allIds) {
        if (this.selectAll) {
            this.selectedIds = [...allIds];
        } else {
            this.selectedIds = [];
        }
    },

    toggleSelect(id) {
        const index = this.selectedIds.indexOf(id);
        if (index > -1) {
            this.selectedIds.splice(index, 1);
            this.selectAll = false;
        } else {
            this.selectedIds.push(id);
        }
    },

    clearSelection() {
        this.selectedIds = [];
        this.selectAll = false;
    },

    submitBulkDelete(formId) {
        if (this.selectedIds.length === 0) return;
        if (confirm(`Are you sure you want to delete ${this.selectedIds.length} selected events?`)) {
            const form = document.getElementById(formId);
            if (form) {
                document.getElementById('bulk-delete-ids').value = JSON.stringify(this.selectedIds);
                form.submit();
            }
        }
    },

    submitBulkCategory(formId, category) {
        if (this.selectedIds.length === 0) return;
        const form = document.getElementById(formId);
        if (form) {
            document.getElementById('bulk-category-ids').value = JSON.stringify(this.selectedIds);
            document.getElementById('bulk-category-type').value = category;
            form.submit();
        }
    }
}));

// Private Lock Store & Manager (5-minute inactivity/unlock timeout)
document.addEventListener('alpine:init', () => {
    Alpine.store('privateLock', {
        isUnlocked: false,
        remainingSeconds: 0,
        timer: null,
        modalOpen: false,

        init() {
            this.checkUnlockState();
            this.timer = setInterval(() => this.tick(), 1000);
        },

        checkUnlockState() {
            const unlockExpiry = sessionStorage.getItem('private_unlock_expiry');
            if (unlockExpiry) {
                const expiryTime = parseInt(unlockExpiry, 10);
                const now = Date.now();
                if (expiryTime > now) {
                    this.isUnlocked = true;
                    this.remainingSeconds = Math.floor((expiryTime - now) / 1000);
                } else {
                    this.lock();
                }
            } else {
                this.isUnlocked = false;
                this.remainingSeconds = 0;
            }
        },

        tick() {
            if (this.isUnlocked) {
                if (this.remainingSeconds > 0) {
                    this.remainingSeconds--;
                } else {
                    this.lock();
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: 'Private events auto-locked after 5 minutes.', type: 'info', duration: 3000 }
                    }));
                }
            }
        },

        unlock(durationSeconds = 300) {
            this.isUnlocked = true;
            this.remainingSeconds = durationSeconds;
            sessionStorage.setItem('private_unlock_expiry', (Date.now() + (durationSeconds * 1000)).toString());
            this.modalOpen = false;
        },

        lock() {
            this.isUnlocked = false;
            this.remainingSeconds = 0;
            sessionStorage.removeItem('private_unlock_expiry');
        },

        toggleLock() {
            if (this.isUnlocked) {
                this.lock();
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: 'Private events locked.', type: 'info', duration: 2500 }
                }));
            } else {
                this.openModal();
            }
        },

        openModal() {
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
        },

        get formattedRemaining() {
            const m = Math.floor(this.remainingSeconds / 60);
            const s = this.remainingSeconds % 60;
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        }
    });
});

Alpine.data('privateLockModal', () => ({
    pinInput: '',
    errorMessage: '',
    loading: false,

    init() {
        this.$watch('$store.privateLock.modalOpen', (val) => {
            if (val) {
                this.pinInput = '';
                this.errorMessage = '';
                this.$nextTick(() => {
                    this.$refs.pinInputField?.focus();
                });
            }
        });
    },

    async submitPin() {
        if (this.pinInput.length !== 4) {
            this.errorMessage = 'Please enter a valid 4-digit PIN.';
            return;
        }

        this.loading = true;
        this.errorMessage = '';

        try {
            const res = await fetch('/private-events/verify-pin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ pin: this.pinInput })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                Alpine.store('privateLock').unlock(300); // 5 minutes
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: data.message || 'Private events unlocked!', type: 'success', duration: 3000 }
                }));
            } else {
                this.errorMessage = data.message || 'Incorrect PIN. Please try again.';
                this.pinInput = '';
                this.$nextTick(() => {
                    this.$refs.pinInputField?.focus();
                });
            }
        } catch (e) {
            this.errorMessage = 'Network error verifying PIN. Please try again.';
        } finally {
            this.loading = false;
        }
    }
}));

// Legacy helper function to open PIN modal globally
window.openPrivatePinModal = () => {
    Alpine.store('privateLock')?.openModal();
};

// Image Dropzone with Preview
Alpine.data('dropzone', (initialPreview = null) => ({
    previewUrl: initialPreview,
    isDragging: false,

    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.generatePreview(file);
        }
    },

    handleDrop(event) {
        this.isDragging = false;
        const file = event.dataTransfer.files[0];
        if (file) {
            const fileInput = this.$refs.fileInput;
            fileInput.files = event.dataTransfer.files;
            this.generatePreview(file);
        }
    },

    generatePreview(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewUrl = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    },

    clearPreview() {
        this.previewUrl = null;
        if (this.$refs.fileInput) {
            this.$refs.fileInput.value = '';
        }
    }
}));

// Pexels Image Picker Component
Alpine.data('pexelsPicker', (initialSelected = {}) => ({
    query: '',
    photos: [],
    page: 1,
    perPage: 9,
    totalPages: 1,
    loading: false,
    isCurated: true,
    errorMessage: '',
    selectedPhoto: initialSelected.id ? initialSelected : null,

    init() {
        // Watch for query changes from external sources if needed
        this.$watch('query', (value) => {
            this.page = 1;
            this.fetchPhotos();
        });
    },

    loadContextualOrCurated(eventName = '') {
        if (this.photos.length > 0) return;
        const trimmed = (eventName || '').trim();
        this.query = trimmed;
        this.fetchPhotos();
    },

    async fetchPhotos() {
        this.loading = true;
        this.errorMessage = '';
        try {
            const params = new URLSearchParams({
                query: this.query || '',
                page: this.page,
                per_page: this.perPage
            });
            const res = await fetch(`/pexels/search?${params.toString()}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success !== false) {
                this.photos = data.photos || [];
                this.isCurated = data.is_curated;
                this.totalPages = Math.ceil((data.total_results || 10) / this.perPage) || 1;
            } else {
                this.errorMessage = data.message || 'Unable to load photos from Pexels.';
            }
        } catch (e) {
            this.errorMessage = 'Network error fetching Pexels photos.';
        } finally {
            this.loading = false;
        }
    },

    selectPhoto(photo) {
        this.selectedPhoto = photo;
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { message: `Selected photo by ${photo.photographer}`, type: 'success', duration: 2500 }
        }));
    },

    clearSelection() {
        this.selectedPhoto = null;
    },

    nextPage() {
        this.page++;
        this.fetchPhotos();
    },

    prevPage() {
        if (this.page > 1) {
            this.page--;
            this.fetchPhotos();
        }
    }
}));

// Keyboard Shortcuts System
document.addEventListener('keydown', (e) => {
    const activeEl = document.activeElement;
    if (activeEl && ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeEl.tagName)) {
        if (e.key === 'Escape') {
            activeEl.blur();
        }
        return;
    }

    if (e.key === 'n' || e.key === 'N') {
        const newEventBtn = document.getElementById('new-event-btn') || document.querySelector('a[href*="events/create"]');
        if (newEventBtn) {
            e.preventDefault();
            newEventBtn.click();
        }
    } else if (e.key === '/') {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
    } else if (e.key === 'Escape') {
        window.dispatchEvent(new CustomEvent('close-modals'));
    } else if (e.key === '?') {
        window.dispatchEvent(new CustomEvent('toggle-shortcuts-help'));
    }
});

// Auto-initialize Flatpickr on DOM elements
document.addEventListener('DOMContentLoaded', () => {
    flatpickr('.datepicker', {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'F j, Y',
        animate: true,
        monthSelectorType: 'static',
        allowInput: true,
    });

    flatpickr('.timepicker', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        altInput: true,
        altFormat: 'h:i K',
        time_24hr: false,
        allowInput: true,
    });
});

// Start Alpine
Alpine.start();
