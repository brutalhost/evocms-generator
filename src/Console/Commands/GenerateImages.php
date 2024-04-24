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

class GenerateImages extends Command
{
    protected $signature = 'generator:images {parentDocId} {--font-size=} {--delta=} {--red=} {--green=} {--blue=} {--empirical=}';
    protected $description = 'Generate images for articles';

    public function handle()
    {
        $id = (int)$this->argument('parentDocId');
        $documents = evo()->runSnippet('DocLister', [
            'api' => 1,
            'parents' => $id,
            'selectFields' => 'c.id,c.pagetitle',
            'tvPrefix' => '',
            'tvList' => config('docshaker.image_field_from_category') . ',dscategories',
        ]);
        $documents = json_decode($documents);

        if (!empty($documents)) {
            foreach ($documents as $document) {
                if (!empty($document)) {
                    if ($document->dscategories !== null && trim($document->dscategories) !== '') {
                        $ids = explode(',', $document->dscategories);

                        // Проверяем, что массив не пуст и первый элемент не пустая строка
                        if (!empty($ids) && !empty($ids[0])) {
                            $firstId = $ids[0];
                        } else {
                            $this->error("Строка с категориями пустая или не содержит чисел");
                            continue;
                        }
                    } else {
                        $this->error("Строка с категориями равна null или пустой строке");
                        continue;
                    }
                    $idCategoryDoc = $firstId;
                    $category = evo()->runSnippet('DocLister', [
                        'api' => 1,
                        'idType' => 'documents',
                        'documents' => $idCategoryDoc,
                        'selectFields' => 'c.id,c.pagetitle',
                        'tvPrefix' => '',
                        'tvList' => config('docshaker.image_field_from_category'),
                        'total' => '1',
                    ]);
                    $category = json_decode($category)->$idCategoryDoc;

                    if (!empty($category)) {
                        if ($category->dsimage !== null && $category->dsimage !== '') {
                            if (file_exists(MODX_BASE_PATH . $category->dsimage)) {
                                $this->processImage($document, $category);
                            } else {
                                $this->error("Изображение не существует по пути: " . $category->dsimage);
                            }
                        } else {
                            $this->error("Категория ".$category->pagetitle.": tv с изображением равно null или является пустой строкой");
                        }
                    }
                }
            }
        }
    }

    public function processImage($document, $category) {
        $city = array(
            $document->pagetitle,
        );

        $img = MODX_BASE_PATH . $category->dsimage; // Ссылка на файл
        $size = getimagesize($img);
        $font_size = $this->option('font-size') ?? 33; // Размер шрифта
        foreach ($city as $gorod) {
            $kol = iconv_strlen($gorod, 'UTF-8');
            $delta = $this->option('delta') ?? 1.5;
            $x = $size[0] / 2 - ($kol * ($font_size / $delta)) / 2;
            switch ($size[2]) { // $size[2] содержит тип изображения
                case IMAGETYPE_JPEG:
                    $pic = imagecreatefromjpeg($img);
                    break;
                case IMAGETYPE_PNG:
                    $pic = imagecreatefrompng($img);
                    break;
                case IMAGETYPE_GIF:
                    $pic = imagecreatefromgif($img);
                    break;
                default:
                    throw new Exception('Неподдерживаемый формат изображения');
            }
            $color = imagecolorallocate($pic,
                (int)$this->option('red') ?? 0,
                (int)$this->option('green') ?? 0,
                (int)$this->option('blue') ?? 0,
            ); // Функция выделения цвета для текста
            $y = $size[1] / 2;//+($font_size*$delta)/2;
            $font = MODX_BASE_PATH.config('docshaker.images_font'); // Ссылка на шрифт
            $degree = 0; // Угол поворота текста в градусах

            if ($kol * ($font_size / $delta) / 2 > $size[0] / 2) {
                $empirical = $this->option('empirical') ?? '12345';
                $newstr = wordwrap($gorod, $kol, $empirical);
                $expl = explode($empirical, $newstr);
                //print_r($expl);
                $kol1 = iconv_strlen($expl[0], 'UTF-8');
                $x1 = $size[0] / 2 - ($kol1 * ($font_size / $delta)) / 2;
                $y1 = $size[1] / 2;//+($font_size*$delta)/2;
                imagettftext($pic, $font_size, $degree, $x1, $y1, $color, $font, $expl[0]);
                $kol2 = iconv_strlen($expl[1], 'UTF-8');
                $x2 = $size[0] / 2 - ($kol2 * ($font_size / $delta)) / 2;
                $y2 = $size[1] / 2 + ($font_size * $delta) / 2 + 30;
                imagettftext($pic, $font_size, $degree, $x2, $y2, $color, $font, $expl[1]);
            } else {
                $x = $size[0] / 2 - ($kol * ($font_size / $delta)) / 2;
                imagettftext($pic, $font_size, $degree, $x, $y, $color, $font, $gorod);
            }
            // Функция нанесения текста
            $path = config('docshaker.images_folder') . $this->translit($gorod) . ".png";
            imagepng($pic, MODX_BASE_PATH . $path); // Сохранение рисунка
            \DocumentManager::edit(['id' => $document->id, config('docshaker.image_field_from_category') => $path]);
            imagedestroy($pic); // Освобождение памяти и закрытие рисунка
            $this->info($this->translit($gorod) . ".png");
        }
    }

    function translit($s)
    {
        $s = (string)$s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'е' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }
}
