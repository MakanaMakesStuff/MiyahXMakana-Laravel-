/// <reference types="vite/client" />

import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// 1. Tell TS about the Window properties
declare global {
    interface Window {
        axios: typeof axios;
        Pusher: typeof Pusher;
        Echo: any;
    }
}

// 2. Setup Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// 3. Setup Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
