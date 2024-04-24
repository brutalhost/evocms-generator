<?php

namespace EvolutionCMS\Generator\Controllers\Generator;

use EvolutionCMS\Generator\Jobs\GenerateCombination;
use Illuminate\Support\Facades\Queue;

class KillProcess
{
    public function __invoke(int $id)
    {
        $output   = null;
        $exitCode = null;
        $os = strtoupper(PHP_OS);

        if (strpos($os, 'WIN') !== false) {
            // Windows
            $command = 'taskkill /F /PID ' . $id;
        } else {
            // Linux
            $command = 'kill ' . $id;
        }

        // Выполнение команды
        exec($command, $output, $exitCode);


        // Проверка успешности выполнения команды
        if ($exitCode === 0) {
            session()->flash('success', 'Процесс успешно завершен.');
        } else {
            session()->flash('error', 'Ошибка при попытке завершить процесс.');
        }

        return back();
    }
}
