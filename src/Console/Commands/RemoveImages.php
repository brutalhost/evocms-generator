<?php

namespace EvolutionCMS\Generator\Console\Commands;

use DocumentParser;
use EvolutionCMS\Generator\Models\Matrix;
use EvolutionCMS\Generator\Services\Combinatorics;
use EvolutionCMS\Models\SiteContent;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use prepare_DL_Extender;

class RemoveImages extends Command
{
    protected $signature = 'generator:remove-images';
    protected $description = 'Remove images from folder';

    public function handle()
    {
        // Получаем путь к папке
        $folderPath = MODX_BASE_PATH . config('docshaker.images_folder');

        // Получаем список всех файлов в папке
        $files = glob($folderPath . '/*');

        // Проверяем, что список файлов не пуст
        if ($files !== false) {
            // Итерируемся по списку файлов и удаляем каждый файл
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }


    }
}
