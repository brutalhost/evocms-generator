<?php

namespace EvolutionCMS\Generator\Controllers;

use Closure;
use EvolutionCMS\Generator\Models\Matrix;
use EvolutionCMS\Generator\Services\Combinatorics;
use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndexController
{
    public function __invoke(Request $request)
    {
        $templates = SiteTemplate::all();
        $matrices = Matrix::all()->map(function (Matrix $matrix) use ($templates) {
            $folders = $matrix->getFolders();
            $foldersPagetitles = [];
            foreach ($folders as $folder) $foldersPagetitles[] = $folder->pagetitle;
            $matrix->table_folders = 'Категории + ' . implode(' + ', $foldersPagetitles);

            if ($matrix->site_content_parent_id == 0) {
                $matrix->table_parent = '0 - Корень сайта';
            } else {
                $parentDoc = DB::table('site_content')->where('id', $matrix->site_content_parent_id)->select('pagetitle')->first();
                $matrix->table_parent = $matrix->site_content_parent_id . ' - ' . $parentDoc->pagetitle;
            }

            $arrayCount = Combinatorics::arrayCategoriesWithArticlesCount($matrix);
            $count = 0;
            foreach ($arrayCount as $value) $count += $value;
            $matrix->table_articles_count = $count;

            $t = $templates->firstWhere('id', $matrix->site_content_template);
            $matrix->table_template = $t->id . ' - ' . $t->templatename;
            return $matrix;
        });

        return view('generator::index', [
            'matrices' => $matrices,
            'templates' => $templates,
            'processList' => $this->processList(),
        ]);
    }

    public function processList() {
        $output = shell_exec('screen -ls');

        $scripts = [];
        $lines = explode("\n", $output);

        foreach ($lines as $line) {
            if (strpos($line, '.generator') !== false) {
                $parts = explode('.', $line);
                $id = trim($parts[0]);
                $name = trim($parts[1]);
                $name = str_replace('(Detached)', '', $name);
                $scripts[] = ['id' => $id, 'name' => $name];
            }
        }

        if (empty($scripts)) {
            $scripts = [];
        }
        return $scripts;
    }
}
