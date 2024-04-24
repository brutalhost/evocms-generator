<?php

namespace EvolutionCMS\Generator\Controllers\Generator;

use EvolutionCMS\Generator\Models\Matrix;
use Illuminate\Support\Facades\Queue;

class GenerateArticles
{
    public function __invoke(Matrix $matrix)
    {
        $pname = 'generator_matrix_' . $matrix->id . '_all';
        if ($this->isEmptySession($pname)) {
            $output = null;
            $exitCode = null;
            $os = strtoupper(PHP_OS);

            if (strpos($os, 'WIN') !== false) {
                $command = 'cmd /C "cd /d ' . EVO_CORE_PATH . ' && start /B php artisan generator:articles ' . $matrix->id;
            } else {
                // Linux
                $command = 'nice -n -5 screen -dmS ' . $pname . ' bash -c "cd ' . EVO_CORE_PATH . '; php artisan generator:articles ' . $matrix->id . '"';
            }

            $pid = exec($command, $output, $exitCode);

            session()->flash('success', 'Процесс запущен');
        } else {
            session()->flash('success', 'Процесс уже запущен, попробуйте позже');
        }

        return back();
    }

    public function isEmptySession(string $scriptName)
    {
        $output = shell_exec('screen -ls ' . $scriptName);
        return strpos($output, $scriptName) === false;
    }
}
