<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <base href="/">
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

    <title>{{ $pagetitle }}</title>
</head>
<body class="container">
@php
    use Illuminate\Support\Facades\Blade;

    $categoriesTvName = config('docshaker.tv_categories_name');
    $entitiesTvName = config('docshaker.tv_entities_name');

    $dlCategory = evo()->runSnippet('DocLister', [
        'api' => 1,
        'idType' => 'documents',
        'documents' => $$categoriesTvName,
        'tvPrefix' => '',
        'tvList' => $introtext,
    ]);

    $dlEntities = evo()->runSnippet('DocLister', [
        'api' => 1,
        'idType' => 'documents',
        'documents' => $$entitiesTvName,
        'sortType' => 'doclist',
        'tvPrefix' => '',
        'tvList' => $introtext,
    ]);

    $arrCategory = array_values((array) json_decode($dlCategory))[0];
    $arrEntities = array_values((array) json_decode($dlEntities));
@endphp

{!! Blade::render(str_replace('&gt;', '>', $content), ['pagetitle' => $pagetitle,'entities' => $arrEntities, 'category' => $arrCategory]); !!}
</body>
</html>