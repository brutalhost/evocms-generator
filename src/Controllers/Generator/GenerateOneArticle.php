<?php

namespace EvolutionCMS\Generator\Controllers\Generator;

use EvolutionCMS\Generator\Models\Matrix;

class GenerateOneArticle
{
    public function __invoke(Matrix $matrix, string $option)
    {
        $output = null;
        $exitCode = null;
        $os = strtoupper(PHP_OS);
        if ($option === 'preview') {
            if (strpos($os, 'WIN') !== false) {
                // Windows
                $command = 'cd /d '.EVO_CORE_PATH.' && php artisan generator:articles "'.$matrix->id.'" --preview';
            } else {
                // Linux
                $command = 'cd '.EVO_CORE_PATH.'; php artisan generator:articles "'.$matrix->id.'" --preview';
            }

            $c = exec($command, $output, $exitCode);


            ob_start();
            // Выводим dump в буфер
            dump($output);
            // Получаем содержимое буфера
            $dumpOutput = ob_get_clean();

            return response('
<div class="modal-dialog modal-dialog-centered">
 <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Пример документа по матрице #'.$matrix->id.'</h5>
    </div>
    <div class="modal-body">
        '.$dumpOutput.'
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
    </div>
 </div>
</div>', 200, ['Content-Type' => 'text/html']);
        } elseif ($option === 'one') {
            if (strpos($os, 'WIN') !== false) {
                // Windows
                $command = 'cd /d '.EVO_CORE_PATH.' && php artisan generator:articles '.$matrix->id.' --one';
            } else {
                // Linux
                $command = 'cd '.EVO_CORE_PATH.'; php artisan generator:articles '.$matrix->id.' --one';
            }

            $c = exec($command, $output, $exitCode);
            session()->flash('success', 'Процесс запущен');
            return back();
        }
    }

}
