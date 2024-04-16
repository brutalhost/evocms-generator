<?php

namespace EvolutionCMS\Generator\Console\Commands;

use DocumentParser;
use EvolutionCMS\Generator\Models\Matrix;
use EvolutionCMS\Generator\Services\Combinatorics;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use prepare_DL_Extender;

class GenerateArticles extends Command
{
    protected $signature   = 'generator:articles {matrix} {--one} {--preview}';
    protected $description = 'Generate articles';

    public function handle()
    {
        $matrixId                           = $this->argument('matrix');
        $matrix                             = Matrix::where('id', (int) $matrixId)->first();
        $arrayCategoriesWithCountOfArticles = Combinatorics::arrayCategoriesWithArticlesCount($matrix);
        //dump($arrayCategoriesWithCountOfArticles);

        $folders = evo()->runSnippet('DocLister', [
            'documents'    => $matrix->folders_id,
            'idType'       => 'documents',
            'api'          => 1,
            'tvList'       => config('docshaker.tv_ignore_categories_field_name'),
            'selectFields' => 'id,pagetitle',
            'tvPrefix'     => '',
            'prepare'      => function ($data) {
                return [
                    'id'             => $data['id'],
                    'pagetitle'      => $data['pagetitle'],
                    'all_categories' => $data[config('docshaker.tv_ignore_categories_field_name')]
                ];
            },
        ]);
        $folders = (array) json_decode($folders);

        foreach ($arrayCategoriesWithCountOfArticles as $categoryId => $count) {
            if ($count == 0) {
                continue;
            }
            $category = evo()->runSnippet('DocLister', [
                'idType'    => 'documents',
                'documents' => $categoryId,
                'api'       => 1,
                'tvList'    => self::addWordIfNotExists($matrix->pagetitle_categories_tvlist, config('docshaker.image_field_from_category')),
                'tvPrefix'  => ''
            ]);
            $category = json_decode($category)->$categoryId;

            $entities    = [];
            $entitiesIds = [];

            foreach ($folders as $folder) {
                $entitie    = evo()->runSnippet('DocLister', [
                    'parents'      => $folder->id,
                    'depth'        => 1,
                    'api'          => 1,
                    'selectFields' => 'c.id',
                    'filters'      => $folder->all_categories === 'true' ? '' : 'tv:'.config('docshaker.tv_categories_name').':regexp:(^|,)'.$categoryId.'(,|$)',
                ]);
                $entities[] = json_decode($entitie);
            }


            foreach ($entities as $entitie) {
                $entitiesIds[] = array_keys((array) $entitie);
            }

            $combinations = Combinatorics::generateCombinations(...$entitiesIds);
            //            dump($combinations);

            foreach ($combinations as $combination) {
                $entities  = evo()->runSnippet('DocLister', [
                    'idType'    => 'documents',
                    'documents' => $combination,
                    'api'       => 1,
                    'tvList'    => $matrix->pagetitle_entities_tvlist,
                    'tvPrefix'  => '',
                    'sortType'  => 'doclist',
                ]);
                $entities  = array_values((array) json_decode($entities));
                $pagetitle = Blade::render('{{'.$matrix->pagetitle_template.'}}',
                    ['category' => $category, 'entities' => $entities]);
                $content   = Blade::render($matrix->site_content_content,
                    ['category' => $category, 'entities' => $entities, 'pagetitle' => $pagetitle]);

                $tvImage = config('docshaker.image_field_from_category');

                $document = [
                    'pagetitle'                            => $pagetitle ?? '',
                    'template'                             => $matrix->site_content_template ?? '',
                    config('docshaker.tv_categories_name') => $categoryId ?? '',
                    'published'                            => 1,
                    'parent'                               => $matrix->site_content_parent_id ?? '',
                    config('docshaker.tv_entities_name')   => $combination ?? '',
                    $tvImage                               => $category->$tvImage ?? '',
                    'content'                              => $content ?? ''
                ];
                dump($document);
                if ($this->option('preview')) {
                    return true;
                }

                \DocumentManager::create($document);

                if ($this->option('one')) {
                    return true;
                }
            }

        }
    }

    static function addWordIfNotExists($str, $word) {
        // Разделяем строку на массив слов
        $words = explode(',', $str);

        // Проверяем, содержится ли искомое слово в массиве
        if (!in_array($word, $words)) {
            // Если в конце строки уже есть запятая, добавляем слово без дополнительной запятой
            if (substr($str, -1) === ',') {
                $words[] = $word;
            } else {
                // Иначе добавляем слово с запятой
                $words[] = ',' . $word;
            }
        }

        // Преобразуем массив обратно в строку
        return implode('', $words);
    }
}