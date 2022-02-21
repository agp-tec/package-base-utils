/*
*
*  Push Notifications por Richard Cardoso
*
*/

'use strict';

// https://web-push-codelab.glitch.me/
const applicationServerPublicKey = 'BFpwqItvvo0aQV9aoGR87z1ONMOSx33-huHS9F9_dzlqXy6d9yyQ30iCVBBZHRN5dz6xPooQQQhrdsm3PLiTllI';

const pushButton = document.querySelector('.js-push-btn');

let isSubscribed = false;
let swRegistration = null;

function urlB64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

if ('serviceWorker' in navigator && 'PushManager' in window) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js', {scope: '/'})
            .then(function(swReg) {
                swRegistration = swReg;
                initializeUI();
            })
            .catch(function(error) {
            });
    });
} else if ('serviceWorker' in navigator) {
    $('.alert-push-no-suported').removeClass('d-none');

    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js');
    });
} else {
    pushButton.classList.add('d-none');
    $('.alert-push-no-suported').removeClass('d-none');
}

function initializeUI() {
    pushButton.addEventListener('click', function() {
        pushButton.disabled = true;
        if (isSubscribed) {
            // TODO: Unsubscribe user
            unsubscribeUser();
        } else {
            subscribeUser();
        }
    });

    // Set the initial subscription value
    swRegistration.pushManager.getSubscription()
        .then(function(subscription) {
            isSubscribed = !(subscription === null);

            if (isSubscribed) {
                console.log('O usuário está inscrito.');
            } else {
                console.log('O usuário não está inscrito.');
            }

            updateBtn();
        });
}

function updateBtn() {
    if (Notification.permission === 'denied') {
        $('.alert-push-bloqueador').removeClass('d-none');
        pushButton.disabled = true;
        pushButton.classList.add('d-none');
        return;
    }

    if (isSubscribed) {
        pushButton.textContent = 'Desativar';
    } else {
        pushButton.textContent = 'Ativar';
    }

    pushButton.disabled = false;
}

function subscribeUser() {
    const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    swRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
        .then(function(subscription) {
            updateSubscriptionOnServer(subscription);
        })
        .catch(function(error) {
            updateBtn();
        });
}

function updateSubscriptionOnServer(subscription) {
    const subscriptionJson = document.querySelector('.js-subscription-json');

    if (Notification.permission === 'denied') {
        updateBtn();
        return;
    }

    if (subscription) {
        const json = JSON.stringify(subscription);

        $.get("/push-subscription", { subscricao: json })
            .done(function() {
                notificacao("Ativado!", "Notificação via banner foi ativada com sucesso.");
                isSubscribed = true;
                updateBtn();
            }).fail(function() {
                notificacao("Ops!", "Não foi possível completar a ação, tente novamente mais tarde.", "danger", "fas fa-times-circle");
            }).always(function() {
                updateBtn();
            });

        subscriptionJson.textContent = json;
    }else{
        $.get("/push-subscription", { subscricao: null })
            .done(function() {
                notificacao("Desativo!", "Sua inscrição foi desativada com sucesso!");
                isSubscribed = false;
            }).fail(function() {
                notificacao("Ops!", "Não foi possível completar a ação, tente novamente mais tarde.", "danger", "fas fa-times-circle");
            }).always(function() {
                updateBtn();
            });
    }
}

function unsubscribeUser() {
    swRegistration.pushManager.getSubscription()
        .then(function(subscription) {
            if (subscription) {
                return subscription.unsubscribe();
            }
        })
        .catch(function(error) {
            console.log('Erro ao cancelar', error);
        })
        .then(function() {
            updateSubscriptionOnServer(null);
        });
}

function notificacao(titulo, mensagem, type= "success", icone = 'fas fa-check-circle'){
    $.notify({
        icon: icone,
        title: titulo,
        message: mensagem,
        target: '_blank'
    },{
        element: 'body',
        type: type,
        allow_dismiss: true,
        delay: 5000,
        animate: {
            enter: 'animated bounceInRight',
            exit: 'animated bounceOutRight'
        },
    });
}
