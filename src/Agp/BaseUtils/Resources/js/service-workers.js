let VERSION = 'v1.0';
let nameApp = "___NAME___";


const keyNames = {
    assets: nameApp + '-assets-' + VERSION,
    routes: nameApp + '-routes-' + VERSION
};

let assets = [
    // JS

    // CSS

    //ICO
    'media/___NAME___/logos/favicon.ico',

    //IMAGES
    'media/___NAME___/icons/icon-128.png',
    'media/___NAME___/icons/icon-rounded-128.png'
];

let routes = [
    '/offline',
];

self.addEventListener("activate", function (event) {
    event.waitUntil(
        caches.open(keyNames.assets)
            .then((cache) => {
                return cache.addAll(assets);
            })
    );

    event.waitUntil(
        caches.open(keyNames.routes)
            .then((cache) => {
                return cache.addAll(routes);
            })
    );

    event.waitUntil(
        caches.keys().then(keys => Promise.all(
            keys.map((key) => {
                if (_getKeyByValue(keyNames, key) === undefined) {
                    return caches.delete(key);
                }
            })
        ))
    );
});

function _getKeyByValue(object, value) {
    return Object.keys(object).find(key => object[key] === value);
}

self.addEventListener("fetch", function(event){

    event.respondWith((async () => {
        try {

            const preloadResponse = await event.preloadResponse;
            if (preloadResponse) {
                return preloadResponse;
            }

            return await caches.match(event.request).then(response => {

                if (navigator.onLine && response) {
                    return response;
                }

                return fetch(event.request);

            });
        } catch (error) {
            const cache = await caches.open(keyNames.routes);
            return await cache.match('/offline');
        }
    })());

});


self.addEventListener('push', function(event) {

    const title = nameApp;
    const options = {
        body: `${event.data.text()}`,
        icon: 'media/___NAME___/icons/icon-128.png',
        badge: 'media/___NAME___/icons/icon-rounded-128.png'
    };
    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('https://agpix.com.br/')
    );
});
