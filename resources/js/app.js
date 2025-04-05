import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import submitLogout from './custom.js';

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("logout-user")?.addEventListener("click", () => submitLogout());
});

// Lazy loading images
const images = document.querySelectorAll('img[loading="lazy"]');

images.forEach((img) => {
  const container = img.parentElement;
  const loader = container.querySelector('[data-loading]');

  img.addEventListener('load', () => {
    if (loader) {
      loader.style.display = 'none';
      img.style.display = "block";
    }
  });

  img.addEventListener('error', () => {
    if (loader) {
      loader.style.display = 'none';
      img.style.display = "block";
      img.src = "fallback_image.jpg";
    }
  });
});
