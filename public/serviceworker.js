self.addEventListener('install', function (event) {
    console.log('Service Worker instalado');
});

self.addEventListener('fetch', function (event) {
    // Puedes personalizar esto para cachear recursos si deseas
});
