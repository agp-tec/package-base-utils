<p align="center"><img src="https://deploy.agapesolucoes.com.br/media/logos/AGP/logo-blue.svg" width="400"></p>

# AGPIX

### Introdução

Pacote para Laravel de funções úteis e classes base.
Contém
- Classe GEOIP: Retorna a localização através do dado IP.
- Classe Utils: Possui as funções genéricas utilizadas nos projetos.
- Models: Contém as classes BaseEntity, BaseRepository, BaseObserver, BaseService.
- BaseObserver: Contém o registro de logs de adição, alteração e remoção da entidade e gerencia o disparo de webhook.
- Traits: Contém as Trais utilizadas nos projetos
- Notifications: Contém as Traits e classes necessárias para a criação de notificações.

### Git do projeto
[Modelo Laravel](https://git.agapesolucoes.com.br/AGP/package-base-utils)

### Fórum de discução
[Fórum AGP](https://www.agapesolucoes.com.br/forum)

### Instalação

Verifique as dependencias desse pacote.

Variáveis de ambiente:
- ``NOTIFICATION_TABLE``: Tabela que contém as notificações (log_notifications).
- ``NOTIFICATION_CONNECTION``: Nome da conexão para a tabela de notificação.


Execute no projeto que deseja instalar o pacote:

```bash
composer require agp/base-utils
```

```bash
php artisan vendor:publish --provider=Agp\BaseUtils\AgpBaseUtilsServiceProvider
```

```bash
php artisan config:cache
```

### Copyright

AGP @ 2020

