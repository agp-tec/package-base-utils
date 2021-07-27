<p align="center"><img src="https://deploy.agapesolucoes.com.br/media/logos/AGP/logo-blue.svg" width="400"></p>

# Base Utils

### Introdução

Pacote para Laravel de funções úteis e classes base.

Contém:
- Classe GEOIP: Retorna a localização através do dado IP.
- Classe Utils: Possui as funções genéricas utilizadas nos projetos.
- Models: Contém as classes BaseEntity, BaseRepository, BaseObserver, BaseService.
- BaseObserver: Contém o registro de logs de adição, alteração e remoção da entidade e gerencia o disparo de webhook.
- Traits: Contém as Trais utilizadas nos projetos.
- Service Worker Padrão.
- Manifest Padrão.
- Serviço de notificação via Push.


## Instalação

Verifique as dependencias desse pacote.
> Para o bom funcionamento deste projeto é essencial seguir a estrutura de arquivos AGP, projeto modular em laravel.

Execute no projeto que deseja instalar o pacote:

```bash
$ composer require agp/base-utils
```

```bash
$ php artisan config:cache
```
<br>
<hr>

## Manifest
### Introdução
O manifest.json é um arquivo JSON que informa ao navegador sobre o seu site no dispositivo móvel ou desktop do usuário. Ter um manifest é exigido pelo Chrome para mostrar o prompt Adicionar à tela inicial.

Quando o usuário instala ou adiciona seu aplicativo da web à tela inicial ou adiciona a um inicializador de aplicativos, o manifest.json fornece ao navegador para que possa tratar o nome, ícones, etc. do seu site.

O arquivo manifest.json contém detalhes do nome do aplicativo do seu site, ícones que ele deve usar, o start_url no qual ele deve iniciar quando iniciado e muitos outros detalhes.

### Instalação

Primeiro você precisa verificar a existência, caso contrário criar:

- Necessário esses três parâmetros abaixo no config.php

    ```json
    'api_client_token' => env('API_CLIENT_TOKEN', null),
    'id_app' => env('ID_APP', '1'),
    'api_agpadmin' => env('API_AGPADMIN'),
    'device_cookie' => env('LOGIN_DEVICE_COOKIE', 'device'),
    ```
  
- Necessário também esse parâmetro abaixo no login.php

    ```json
    'device_cookie' => env('LOGIN_DEVICE_COOKIE', 'device'),
    ```
<br>

Após todas as variáveis verificadas ou inseridas você pode começar a instalar o pacote em seu projeto:

```bash
$ php artisan install:service-js
```
Ao completar a instalação você precisa fazer com que o seu projeto utilize-o. 

Para isso bastar adicionar no topo do seu `<head>` do `app.blade.php` a tag link, como no exemplo a baixo:

```html
<head>
    <link rel="manifest" href="{{asset('manifest.json')}}">
    
    ...
</head>
```

Você pode confirmar e pronto, um manifest padrão é copiado para o diretório `public` do seu projeto.


### Customização

```json
    "start_url": "/", // Uma string que representa o URL de início do aplicativo da web.
    "description": "Um projeto AGP", // Uma string na qual os desenvolvedores podem explicar o que o aplicativo faz.
    "background_color": "#FFFFFF", // Define uma cor de fundo de espaço reservado para a página do aplicativo a ser exibida antes que seu stylesheet seja carregado.
    "theme_color": "#FFFFFF", // Uma string que define a cor padrão do tema para o aplicativo.
    "display": "standalone", // Uma string que determina o modo de exibição preferido dos desenvolvedores para o site.
```

[ Ver mais opções ](https://developer.mozilla.org/en-US/docs/Web/Manifest#members)

### Adicional 

Recomendamos também que você inclua as tags HTML listadas abaixo em seu `app.blade.php`

```html
<head>
    <link rel="manifest" href="{{asset('manifest.json')}}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{asset('media/<NomeDoProjeto>/icons/icon-420.png')}}">
    <link rel="apple-touch-startup-image" href="{{asset('media/<NomeDoProjeto>/logos/png/splashscreen.png')}}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }} • <Sub titulo>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#FFFFFF"> <!--Uma string que define a cor padrão do tema para o aplicativo.-->

    <meta name="description" content="<Slogan do app>"/>
    <meta property="og:title" content="{{ config('app.name') }} • <Sub titulo>"/>
    <meta property="og:url" content="{{ config('app.url') }}"/>
    <meta property="og:image" content="{{asset(media/<NomeDoProjeto>/logos/png/logo-share-green.png")}}"/>
    <meta property="og:image:url" content="{{asset('media/<NomeDoProjeto>/logos/png/logo-share-green.png)}}"/>
    <meta property="og:image:secure_url" content="{{asset('media/<NomeDoProjeto>/logos/png/logo-share-blue.png)}}"/>

    ...
</head>
```

## Service Worker

### Introdução
Um service worker é um tipo especial de worker baseado em eventos. Na prática, ele é um arquivo JavaScript que pode controlar as páginas do site ao qual ele está associado, interceptando e modificando requisições e a navegação em si.

O service worker armazena os recursos necessários no cache do navegador e quando o usuário visitar o aplicativo novamente, o service worker verifica o cache e retorna com o resultado antes mesmo de verificar a rede.

Ele gerencia as notificações por push e ajuda a criar o aplicativo web offline usando a API de cache do navegador.

### Instalação

Primeiro você precisa verificar a existência, caso contrário criar:

- Necessário esses quatros parâmetros abaixo no config.php

    ```json
    'api_client_token' => env('API_CLIENT_TOKEN', null),
    'id_app' => env('ID_APP', '1'),
    'api_agpadmin' => env('API_AGPADMIN'),
    'device_cookie' => env('LOGIN_DEVICE_COOKIE', 'device'),
    ```
<br>

Após todas as variáveis verificadas ou inseridas você pode começar a instalar o pacote em seu projeto:

```bash
$ php artisan install:service-js
```
> Esse comando copia os arquivos de `media` e `js` para o `resources` do seu projeto.


É necessário também instalar os arquivos de estilos e js.
No seu projeto você precisa adicionar a linha de código em seu `webpack.mix.js` que está presente na raiz do projeto.


```script
mix.scripts('resources/js/service-workers.js', 'public/sw.js');
```

Depois basta executar
```bash
$ yarn run dev

-- ou --

$ npm run dev
```

###Customização

Em resources/js do seu projeto foi criado um arquivo service-workers.js, nele possui uma variavel chamada `assets` (arquivos) e `routes` (rotas) iram ficar armazenados no cache do navegador.

###Custumizando a view offline

```bash
 $ php artisan vendor:publish --tag=base-utils-config       
```
Um arquivo de configuração é criado, basta trocar a view padrão!


## Utilização do push
> Para utilizar a notificação via push você precisa ter feito o passo anterior(Instalação do ServiceWorker).

### Ativação

View de Ativição de mensagens, onde pode ser adicionado como por exemplo no `_quick-pane.blade.php`
```html
@include('BaseUtils::push.ativacao')
```

View com os modais de ajuda para o usuario;
```html
@include('BaseUtils::push.help')
```

### Exemplo para notificar

Você consegue notificar os usuarios que possuem uma inscrição, basta criar uma classe de notificação laravel;
```bash
$ php artisan make:notification <NomeDaNotificação>
```

Após criar a classe de notificação você precisa adicionar os metodos do push como no exemplo a baixo.

```php
<?php

namespace App\Notifications;

use Agp\BaseUtils\Notifications\PushChannel;

class ExampleNotify extends Notification
{
    use Queueable;

    private $user;
    private $mensagem;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $mensagem = 'Exemplo de notificação via Push')
    {
        $this->user = $user;
        $this->mensagem = $mensagem;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [PushChannel::class];
    }

    /**
     * Get the push representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toPush($notifiable)
    {
        return [
            'notificacao' => $this->mensagem,
            'dispositivos' => $this->user->dispositivos->whereNotNull('subscricao') // Array de UsuariosDispositivos;
        ];
    }
}

```


> O pacote remove a subinscrição dos dispositivo que não retornar resposta de sucesso.

Observações:


E pronto, agora só depende de você. Crie notificações execelente! 😉

<hr>

> por Richard Pereira Cardoso

### Git do projeto
[Modelo Laravel](https://git.agapesolucoes.com.br/AGP/package-base-utils)

### Fórum de discução
[Fórum AGP](https://www.agapesolucoes.com.br/forum)

### Copyright

AGP @ 2020

