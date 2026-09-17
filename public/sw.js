// public/sw.js
self.addEventListener('install', (event) => {
    console.log('Service Worker installing.');
});

self.addEventListener('activate', (event) => {
    console.log('Service Worker activating.');
});

self.addEventListener('fetch', (event) => {
    // Contoh sederhana: hanya log request
    // Anda bisa menambahkan logika caching di sini
    console.log('Fetching:', event.request.url);
});