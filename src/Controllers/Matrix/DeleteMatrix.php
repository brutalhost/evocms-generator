<?php

namespace EvolutionCMS\Generator\Controllers\Matrix;

use EvolutionCMS\Generator\Jobs\RemoveMatrix;
use EvolutionCMS\Generator\Models\Matrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;

class DeleteMatrix
{
    public function __invoke(Matrix $matrix)
    {
//        $matrix->entitiesFolders()->detach();
        $matrix->deleteOrFail();
        session()->flash('success', 'Матрица удалена');

        return back();
    }
}