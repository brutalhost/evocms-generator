<?php

namespace EvolutionCMS\Generator\Console\Commands;

use DocumentParser;
use EvolutionCMS\Generator\Models\Matrix;
use EvolutionCMS\Generator\Services\Combinatorics;
use EvolutionCMS\Models\Category;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Models\SiteTmplvar;
use EvolutionCMS\Models\SiteTmplvarContentvalue;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use Illuminate\Console\Command;
use prepare_DL_Extender;

class Init extends Command
{
    protected $signature   = 'generator:init';
    protected $description = 'Create templates, tvs, sitecontent';

    public function handle()
    {
        $category = Category::create(['category' => 'DocShaker']);

        //
        //  Переменные шаблонов
        //
        $tv_entities = SiteTmplvar::create([
            'name'         => 'dsentities',
            'type'         => 'custom_tv:selector',
            'caption'      => 'Сущности',
            'category'     => $category->id,
            'default_text' => '',
        ]);

        $tv_categories = SiteTmplvar::create([
            'name'         => 'dscategories',
            'type'         => 'custom_tv:selector',
            'caption'      => 'Категории',
            'category'     => $category->id,
            'default_text' => '',
        ]);

        $tv_ignore_categories_field = SiteTmplvar::create([
            'name'         => 'dsignorecategories',
            'type'         => 'option',
            'caption'      => 'Игнорировать поле "категории" у потомков',
            'elements'     => 'true||false',
            'category'     => $category->id,
            'default_text' => 'false',
        ]);

        $tv_image = SiteTmplvar::create([
            'name'         => 'dsimage',
            'type'         => 'image',
            'caption'      => 'Изображение',
            'category'     => $category->id,
            'default_text' => '',
        ]);


        //
        //  Шаблоны
        //

        $template_generator = SiteTemplate::create([
            'templatename' => 'DS Генератор',
            'description'  => 'Шаблон генератора, в который помещаются папки категорий и сущностей.',
            'content'      => '',
            'selectable'   => 0,
            'category'     => $category->id,
        ]);

        $template_article = SiteTemplate::create([
            'templatename' => 'DS Запись',
            'description'  => 'Шаблона записи.',
            'content'      => '',
            'category'     => $category->id,
        ]);

        $template_test = SiteTemplate::create([
            'templatename' => 'DS Тест',
            'description'  => 'Шаблон для тестовой записи. Аннотации - это tvlist, а контент парсит Blade теги как при генерации через модуль.',
            'content'      => '',
            'templatealias' => 'dstest',
            'category'     => $category->id,
        ]);

        // Категории

        $template_category_folder = SiteTemplate::create([
            'templatename' => 'DS Категория - папка',
            'description'  => 'Шаблон папки, содержащей категории.',
            'content'      => '',
            'selectable'   => 0,
            'category'     => $category->id,
        ]);

        $template_category = SiteTemplate::create([
            'templatename' => 'DS Категория',
            'description'  => 'Шаблон категории, используемый при генерации ресурсов.',
            'content'      => '',
            'category'     => $category->id,
        ]);

        // Сущности

        $template_entitie_folder = SiteTemplate::create([
            'templatename' => 'DS Сущность - папка',
            'description'  => 'Шаблон папки, содержащей сущности.',
            'content'      => '',
            'category'     => $category->id,
        ]);

        $template_entitie = SiteTemplate::create([
            'templatename' => 'DS Сущность',
            'description'  => 'Шаблон сущности, используемый при генерации ресурсов.',
            'content'      => '',
            'category'     => $category->id,
        ]);

        //
        //  Привязка tvs к шаблонам
        //

        SiteTmplvarTemplate::create(['tmplvarid' => $tv_categories->id, 'templateid' => $template_article->id]);
        SiteTmplvarTemplate::create(['tmplvarid' => $tv_entities->id, 'templateid' => $template_article->id]);
        SiteTmplvarTemplate::create(['tmplvarid' => $tv_image->id, 'templateid' => $template_article->id]);

        SiteTmplvarTemplate::create(['tmplvarid' => $tv_categories->id, 'templateid' => $template_test->id]);
        SiteTmplvarTemplate::create(['tmplvarid' => $tv_entities->id, 'templateid' => $template_test->id]);
        SiteTmplvarTemplate::create(['tmplvarid' => $tv_image->id, 'templateid' => $template_test->id]);

        SiteTmplvarTemplate::create([
            'tmplvarid' => $tv_ignore_categories_field->id, 'templateid' => $template_entitie_folder->id
        ]);

        SiteTmplvarTemplate::create(['tmplvarid' => $tv_categories->id, 'templateid' => $template_entitie->id]);

        SiteTmplvarTemplate::create(['tmplvarid' => $tv_image->id, 'templateid' => $template_category->id]);

        //
        //  Генерация документов
        //

        $sitecontent_folder_generator = SiteContent::create([
            'pagetitle' => 'Генератор',
            'published' => 1,
            'parent'    => 0,
            'isfolder'  => 1,
            'template'  => $template_generator->id,
            'hidemenu'  => 1,
            'alias' => 'dsgenerator'
        ]);

        $sitecontent_folder_category = SiteContent::create([
            'pagetitle' => 'Категории',
            'published' => 1,
            'parent'    => $sitecontent_folder_generator->id,
            'isfolder'  => 1,
            'template'  => $template_category_folder->id,
            'hidemenu'  => 1,
        ]);

        $sitecontent_folder_brands = SiteContent::create([
            'pagetitle' => 'Бренды',
            'published' => 1,
            'parent'    => $sitecontent_folder_generator->id,
            'isfolder'  => 1,
            'template'  => $template_entitie_folder->id,
            'hidemenu'  => 1,
        ]);

        $sitecontent_folder_locations = SiteContent::create([
            'pagetitle' => 'Локации',
            'published' => 1,
            'parent'    => $sitecontent_folder_generator->id,
            'isfolder'  => 1,
            'template'  => $template_entitie_folder->id,
            'hidemenu'  => 1,
        ]);

        SiteTmplvarContentvalue::create([
            'tmplvarid' => $tv_ignore_categories_field->id, 'contentid' => $sitecontent_folder_brands->id,
            'value'     => 'false'
        ]);
        SiteTmplvarContentvalue::create([
            'tmplvarid' => $tv_ignore_categories_field->id, 'contentid' => $sitecontent_folder_locations->id,
            'value'     => 'true'
        ]);

        // Заполнение примерами записей

        $sitecontent_category_repair = SiteContent::create([
            'pagetitle' => 'Ремонт стиральных машин',
            'published' => 1,
            'parent'    => $sitecontent_folder_category->id,
            'template'  => $template_category->id,
        ]);

        $sitecontent_category_check = SiteContent::create([
            'pagetitle' => 'Проверка счётчиков',
            'published' => 1,
            'parent'    => $sitecontent_folder_category->id,
            'template'  => $template_category->id,
        ]);

        SiteTmplvarContentvalue::create([
            'tmplvarid' => $tv_image->id, 'contentid' => $sitecontent_category_repair->id,
            'value'     => 'assets/images/noimage.jpg'
        ]);
        SiteTmplvarContentvalue::create([
            'tmplvarid' => $tv_image->id, 'contentid' => $sitecontent_category_check->id,
            'value'     => 'assets/images/noimage.jpg'
        ]);

        // Сущности

        $sitecontent_indesit = SiteContent::create([
            'pagetitle' => 'Indesit',
            'published' => 1,
            'parent'    => $sitecontent_folder_brands->id,
            'template'  => $template_entitie->id,
        ]);
        SiteTmplvarContentvalue::create([
            'tmplvarid' => $tv_categories->id, 'contentid' => $sitecontent_indesit->id, 'value' => implode(',',
                [$sitecontent_category_check->id, $sitecontent_category_repair->id])
        ]);

        $sitecontent_bosch = SiteContent::create([
            'pagetitle' => 'Bosch',
            'published' => 1,
            'parent'    => $sitecontent_folder_brands->id,
            'template'  => $template_entitie->id,
        ]);
        SiteTmplvarContentvalue::create([
            'tmplvarid' => $tv_categories->id, 'contentid' => $sitecontent_bosch->id,
            'value'     => $sitecontent_category_check->id
        ]);

        $locations = ['Москва', 'Санкт-Петербург', 'Екатеринбург'];
        foreach ($locations as $location) {
            $loc = SiteContent::create([
                'pagetitle' => $location,
                'published' => 1,
                'parent'    => $sitecontent_folder_locations->id,
                'template'  => $template_entitie->id,
            ]);
            SiteTmplvarContentvalue::create([
                'tmplvarid' => $tv_categories->id, 'contentid' => $loc->id, 'value' => ' '
            ]);
        }

        $test_document = [
            'pagetitle'                            => 'Тестовый документ',
            'template'                             => $template_test->id,
            'published'                            => 1,
            'parent'                               => 0,
            'hidemenu'                             => 1,
            config('docshaker.tv_entities_name')   => $sitecontent_indesit->id.','.$sitecontent_bosch->id,
            config('docshaker.tv_categories_name') => $sitecontent_category_repair->id,
            'content' => "<h1>{{ \$pagetitle }}</h1>
<p>Нажмите кнопку 'Посмотреть', и вы увидите, что все переменные здесь автоматически заполнятся из указанных сущностей и категории.</p>
<p>Pagetitle страницы генерируется в первую очередь, поэтому к нему можно получить доступ прямо из тела контента. Это относится к документам, генерируемым через модуль DocShaker.</p>
<p>Добавьте в поле 'Аннотация (введение)' через запятую названия TV полей, и они автоматически станут доступны для переменных сущностей и категорий (если они установлены для их шаблонов).</p>
<p>{{ \$entities[0]->pagetitle }} лучший бренд из {{ \$entities[1]->pagetitle }}. {{ \$category->pagetitle }} - наша главная миссия.</p>
<p>Как получить список доступных переменных:</p>
<p>Это категория @dump(\$category)</p>
<p>Это сущности @dump(\$entities)</p>"
        ];
        \DocumentManager::create($test_document);

        $string = "<?php
        return [
            // Generator
            'generator_folder_id' => ".$sitecontent_folder_generator->id.",
            'generator_folder_alias' => '".$sitecontent_folder_generator->alias."',
            'categories_folder_id' => ".$sitecontent_folder_category->id.",

            'entities_template_id' => ".$template_entitie->id.",
            'entities_folder_template_id' => ".$template_entitie_folder->id.",

            'image_field_from_category' => '".$tv_image->name."',
            'images_folder' => 'assets/images/docshaker/',
            'images_font_path' => MODX_BASE_PATH.'assets/fonts/ttf/ofont.ru_AGGaramond Cyr.ttf',

            // TV
            'tv_entities' => ".$tv_entities->id.",
            'tv_entities_name' => '".$tv_entities->name."',
            'tv_categories' => ".$tv_categories->id.",
            'tv_categories_name' => '".$tv_categories->name."',
            'tv_ignore_categories_field' => ".$tv_ignore_categories_field->id.",
            'tv_ignore_categories_field_name' => '".$tv_ignore_categories_field->name."',
        ];";
        $path   = EVO_CORE_PATH.'/custom/config/docshaker.php';
        file_put_contents($path, $string, LOCK_EX);
    }
}
