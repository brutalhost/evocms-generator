<?php

namespace EvolutionCMS\Generator\Services;

use EvolutionCMS\Generator\Models\Matrix;
use Illuminate\Support\Facades\DB;
use stdClass;

class Combinatorics
{
    public static function countPermutations(...$arrays) {
        $count = 1;

        foreach ($arrays as $array) {
            $count *= count($array);
        }

        return $count;
    }

    public static function arrayCategoriesWithArticlesCount(Matrix $matrix) {
        $categories = DB::table('site_content')->where('parent', config('docshaker.categories_folder_id'))->select(['id'])->get();
        $result = [];

        foreach ($categories as $category) {
            $folders = evo()->runSnippet('DocLister', [
                'api' => 1,
                'idType' => 'documents',
                //'sortType' => 'doclist',
                'selectFields' => 'id, pagetitle',
                'tvList' => config('docshaker.tv_ignore_categories_field_name'),
                'tvPrefix' => '',
                'documents' => $matrix->folders_id,
                'prepare' => function ($data) {
                    return [
                      'id' => $data['id'],
                      'pagetitle' => $data['pagetitle'],
                      'ignorecategories' => $data[config('docshaker.tv_ignore_categories_field_name')],
                    ];
                }
            ]);

            $folders = json_decode($folders);
            $count = 1;

            foreach ($folders as $folder) {
                $count *= self::getCountOfCategoryWithEntities($category->id, $folder);
                // Значит категория не будет генерироваться
                if ($count == 0) break;
            }

            $result[$category->id] =  $count;
        }

        return $result;
    }

    public static function getCountOfCategoryWithEntities(int $categoryId, stdClass $folder) {
        if ($folder->ignorecategories === 'true') {
            return DB::table('site_content')->where('parent', $folder->id)->count();
        }

        $docs = evo()->runSnippet('DocLister', [
            'api' => 1,
            'filters' =>'tv:'.config('docshaker.tv_categories_name').':regexp:(^|,)'.$categoryId.'(,|$)',
            'parents' => $folder->id,
        ]);
        return count((array) json_decode($docs));
    }

    // Объявление функции combine() вне метода generateCombinations()
    private static function combine($arrays, $index, $current, &$result) {
        if ($index == count($arrays)) {
            $result[] = $current;
            return;
        }

        foreach ($arrays[$index] as $item) {
            // Добавляем запятую только если текущая комбинация не пуста
            $newCurrent = $current ? $current . ',' . $item : $item;
            self::combine($arrays, $index + 1, $newCurrent, $result);
        }
    }

    public static function generateCombinations(...$arrays) {
        $result = [];

        if (count($arrays) > 0) {
            self::combine($arrays, 0, '', $result);
        }

        return $result;
    }
}