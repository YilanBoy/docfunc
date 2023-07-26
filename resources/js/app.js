import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

Alpine.plugin(focus);
Alpine.plugin(collapse);
window.Alpine = Alpine;

Alpine.data('alertComponent', (alertObject = null) => ({
    alert: alertObject,
    openAlertBox: false,
    alertBackgroundColor: '',
    alertMessage: '',
    successIcon: `<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mr-2 text-white"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
    infoIcon: `<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mr-2 text-white"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
    warningIcon: `<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mr-2 text-white"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
    dangerIcon: `<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mr-2 text-white"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>`,
    showAlert(status, message) {
        switch (status) {
            case 'success':
                this.alertBackgroundColor = 'bg-green-500';
                this.alertMessage = `${this.successIcon} ${message}`;
                break;
            case 'info':
                this.alertBackgroundColor = 'bg-blue-500';
                this.alertMessage = `${this.infoIcon} ${message}`;
                break;
            case 'warning':
                this.alertBackgroundColor = 'bg-yellow-500';
                this.alertMessage = `${this.warningIcon} ${message}`;
                break;
            case 'danger':
                this.alertBackgroundColor = 'bg-red-500';
                this.alertMessage = `${this.dangerIcon} ${message}`;
                break;
        }

        this.openAlertBox = true;
    },
}));

Alpine.start();
