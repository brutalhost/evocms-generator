@verbatim<h2 class="code-line" data-line-start="0" data-line-end="1"><a id="_0"></a>Помощь</h2>
<h3 class="code-line" data-line-start="1" data-line-end="2"><a id="___1"></a>Форма создания матрицы</h3>
<ul>
    <li class="has-line-data" data-line-start="2" data-line-end="3"><strong>Папки сущностей</strong> - их порядок влияет только на порядок указателей <code>$entities[0]...$entities[n]</code> в шаблонизаторе, подробнее ниже</li>
    <li class="has-line-data" data-line-start="3" data-line-end="4"><strong>Шаблон записей</strong> - указывается целевой шаблон для генерируемых записей</li>
    <li class="has-line-data" data-line-start="4" data-line-end="5"><strong>ID родительского документа</strong> - папка, в которой будут находиться записи</li>
</ul>
<h3 class="code-line" data-line-start="5" data-line-end="6"><a id="_5"></a>Шаблонизация</h3>
<ul>
    <li class="has-line-data" data-line-start="6" data-line-end="7"><strong>tvList - Категория</strong> - здесь указываются tv категорий, которые станут доступны в полях шаблонизации</li>
    <li class="has-line-data" data-line-start="7" data-line-end="10"><strong>tvList - Сущности</strong> - аналогично полю <em>tvList - Категория</em><br>
        Поля <strong>Заголовок</strong> и <strong>Контент</strong> заполняются согласно правилам из <a href="https://laravel.su/docs/8.x/blade">документации Laravel</a>.<br>
        Какие переменные доступны: $category, $entities[n], $pagetitle (только для контентной части).</li>
</ul>
<h4 class="code-line" data-line-start="10" data-line-end="11"><a id="_10"></a>Заголовок</h4>
<p class="has-line-data" data-line-start="11" data-line-end="16">Заполняется так, как если бы все содержимое находилось внутри директивы <code>{{ $вашконтент }}</code>, что аналогично <code>echo($вашконтент)</code>.<br>
    Пример заполнения: <code>$category-&gt;pagetitle . ' в ' . $entities[0]-&gt;pagetitle</code> превратится в "<strong>Ремонт стиральных машин</strong> в <strong>Москва</strong>". При условии, что pagetitle у категории будет <em>Ремонт стиральных машин</em>, а pagetitle первой указанной сущности - <em>Москва</em>.<br>
    Чтобы появилось склонение сущности города, надо создать соответствующее tv поле, привязать его к шаблону и добавить в поле формы <strong>tvList - Сущности</strong>. Тогда к нему можно будет обращаться как к <code>$entities[n]-&gt;вашtv</code>.<br>
    Проверку на непустое значение нужно делать самостоятельно. Например, если поле не заполнено, то можно вывести его так: <code>$category-&gt;pagetitle . 'в' . (empty($entities[0]-&gt;tv) ? $entities[0]-&gt;pagetitle : $entities[0]-&gt;tv)</code>.<br>
    Если указано несколько папок сущностей в <strong>Папки сущностей</strong> - обращаться к ним через <code>$entities[n]</code>, где <em>n</em> - порядковый номер, начиная с 0.</p>
<h4 class="code-line" data-line-start="16" data-line-end="17"><a id="_16"></a>Контент</h4>
<p class="has-line-data" data-line-start="17" data-line-end="19">Всё то же самое, что и у поля <strong>Заголовок</strong>. Заполняется как Blade шаблон, то есть директивы <code>{{ $var }}</code> нужно проставлять вручную. Доступна переменная <code>$pagetitle</code>.<br>
    Для проверки корректности заполнения отображения воспользуйтесь тестовым документом, который находится в корневой папке генератора.</p>
@endverbatim
