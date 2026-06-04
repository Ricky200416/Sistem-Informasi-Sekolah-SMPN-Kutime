import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;
Alpine.start();

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

// Optional: auto close alert after 5 seconds
document.addEventListener('alpine:init', () => {
    Alpine.data('alert', () => ({
        show: true,
        init() {
            setTimeout(() => {
                this.show = false;
            }, 5000);
        }
    }));
});