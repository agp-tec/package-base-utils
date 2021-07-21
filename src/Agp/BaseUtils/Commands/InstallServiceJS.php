<?php

namespace Agp\BaseUtils\Commands;

use Agp\BaseUtils\Model\Service\UserService;
use Illuminate\Console\Command;

class InstallServiceJS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:service-js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instala os arquivos do pacote em seu projeto!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function copy_directory($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->line("Instalando...");

        $aplicativo = UserService::getAplicativo();

        if ($aplicativo == null)
            return $this->error('Aplicativo não encontrado ou ID APP inválido.');

        if ($this->confirm('Instalar Manifest?', true)) {
            copy(__DIR__.'/../Resources/manifest.json', public_path("manifest.json"));

            $str=file_get_contents(public_path('manifest.json'));
            $str=str_replace('___NAME___', str_replace(" ", "", $aplicativo->nome), $str);
            file_put_contents(public_path('manifest.json'), $str);
        }

        if ($this->confirm('Instalar Service Worker e Notificações via Push?', true)){
            $this->copy_directory(__DIR__.'/../Resources/media/push', resource_path('metronic/media/push'));
            $this->copy_directory(__DIR__.'/../Resources/js', resource_path('js'));

            $str=file_get_contents(resource_path('js/service-workers.js'));
            $str=str_replace('___NAME___', str_replace(" ", "", $aplicativo->nome), $str);
            file_put_contents(resource_path('js/service-workers.js'), $str);

            $addScrits = "window.AGPPush = require('./push.js');";
            if(!(strpos(file_get_contents(resource_path('js/scripts.js')), $addScrits) !== false)) {
                $fp = fopen(resource_path('js/scripts.js'), "a+");
                fwrite($fp, chr(10).$addScrits);
                fclose($fp);
            }
        }

        $this->info("Dependências instaladas com sucesso!");

        return 0;
    }

}
