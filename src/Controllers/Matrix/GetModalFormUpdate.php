<?php

namespace EvolutionCMS\Generator\Controllers\Matrix;

use EvolutionCMS\Generator\Jobs\RemoveMatrix;
use EvolutionCMS\Generator\Models\Matrix;
use EvolutionCMS\Models\SiteTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;

class GetModalFormUpdate
{
    public function __invoke(Matrix $matrix)
    {
        $form = Blade::render('generator::generator.form', [
            'matrix' => $matrix,
            'matrices' => Matrix::all(),
            'templates' => SiteTemplate::all(),
        ]);
        
        return response('
<div class="modal-dialog modal-dialog-centered">
 <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Редактирование матрицы #' . $matrix->id . '</h5>
    </div>
    <div class="modal-body">
        <form hx-swap="innerHTML" hx-post="' . route('generator::matrix.update', $matrix) . ' action="' . route('generator::matrix.update', $matrix) . '" method="post">
      ' . $form . '
        </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
    </div>
 </div>
</div>', 200, ['Content-Type' => 'text/html']);
    }
}