<?php namespace EvolutionCMS\Generator;

use Composer\Command\InitCommand;
use EvolutionCMS\Generator\Console\Commands\GenerateArticles;
use EvolutionCMS\Generator\Console\Commands\Init;
use EvolutionCMS\Generator\Console\Commands\Sleep1000;
use EvolutionCMS\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Если указать пустую строку, то сниппеты и чанки будут иметь привычное нам именование
     * Допустим, файл test создаст чанк/сниппет с именем test
     * Если же указан namespace то файл test создаст чанк/сниппет с именем generator#test
     * При этом поддерживаются файлы в подпапках. Т.е. файл test из папки subdir создаст элемент с именем subdir/test
     */
    protected $namespace = 'generator';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->registerRoutingModule(
            'DocShaker',
            __DIR__.'/../routes.php',
            'fa fa-hashtag'
        );
    }

    public function boot()
    {
        $this->commands([
            GenerateArticles::class,
            Init::class
        ]);
        $this->loadViewsFrom(__DIR__.'/../views', $this->namespace);
        $this->loadTranslationsFrom(__DIR__.'/../lang', $this->namespace);
        $this->loadMigrationsFrom(__DIR__.'/../resources/migrations');

        $this->publishes([
            __DIR__.'/../publishable/assets' => MODX_BASE_PATH.'assets',
            __DIR__.'/../publishable/views' => MODX_BASE_PATH.'views',
        ]);
    }
}