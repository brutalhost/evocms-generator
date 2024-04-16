<?php

namespace EvolutionCMS\Generator\Controllers\Generator;

use EvolutionCMS\Generator\Models\Matrix;

class GenerateOneArticle
{
    public function __invoke(Matrix $matrix, string $option)
    {
        $output = null;
        $exitCode = null;
        if ($option === 'preview') {
            $c      = exec('cd '.EVO_CORE_PATH.'; php artisan generator:articles '.$matrix->id.' --preview', $output,
                $exitCode);

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
            $c      = exec('cd '.EVO_CORE_PATH.'; php artisan generator:articles '.$matrix->id.' --one', $output, $exitCode);
            session()->flash('success', 'Процесс запущен');
            return back();
        }
    }

}