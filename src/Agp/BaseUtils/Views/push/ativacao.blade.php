<h5 class="font-weight-bold mb-5">Notificações via Banner</h5>
<img class="w-100 mb-10" src="{{ asset('media/push/example.png') }}" alt="">

<div class="text-center">
    <button disabled class="js-push-btn btn btn-success mb-5">
        Ativar
    </button>
</div>

<section class="subscription-details js-subscription-details d-none">
    <pre class="language-json"><code class="js-subscription-json language-json"></code></pre>
</section>

<div class="d-none alert-push-no-suported">
    <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-5 d-none">
        <span class="svg-icon svg-icon-danger mr-5">
            {{ Metronic::getSVG('media/svg/icons/Code/Warning-2.svg', 'svg-icon-lg') }}
        </span>
        <div class="d-flex flex-column flex-grow-1 mr-2">
            <span class="font-weight-bold text-danger font-size-lg mb-1">Não suportado</span>
            <span class="text-muted font-size-sm">Notificações via push não suportadas neste navegador.</span>
        </div>
    </div>
</div>

<div class="d-none cursor-pointer alert-push-bloqueador" title="Desbloquear?" data-toggle="modal" data-target="#modalDesbloquearPush">
    <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-5 d-none">
        <span class="svg-icon svg-icon-danger mr-5">
            {{ Metronic::getSVG('media/svg/icons/Code/Warning-2.svg', 'svg-icon-lg') }}
        </span>
        <div class="d-flex flex-column flex-grow-1 mr-2">
            <span class="font-weight-bold text-danger font-size-lg mb-1">Bloqueado!</span>
            <span class="text-dark-50 font-size-sm">Para que conseguimos ativar a notifição, você precisa nós autorizar.</span>
        </div>
    </div>
</div>
