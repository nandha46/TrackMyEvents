import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import submitLogout from './custom.js';

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("logout-user")?.addEventListener("click", () => submitLogout());
});
