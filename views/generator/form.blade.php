@csrf

<div class="form-group">
    <label for="entfolders">Папки сущностей</label>
    @php
        $field = isset($matrix) ? $matrix->id . 'entfolders' : 'entfolders';
        $value = isset($matrix) ? $matrix->folders_id : '';
        $options = array (
          'caption' => 'Папки сущностей',
          'type' => 'custom_tv:selector',
          'note' => 'Будут обрабатываться в указанном порядке',
          'elements' => '',
          'default_text' => '',
        );
        $row = [
            'type'         => $options['type'],
            'name'         => $field,
            'caption'      => $options['caption'],
            'id'           => $field,
            'default_text' => '',
            'value'        => old('tv'.$field, $value),
            'elements'     => isset($options['elements']) ? $options['elements'] : '',
        ];
        echo renderFormElement(
            $row['type'],
            $row['name'],
            '',
            $row['elements'],
            $row['value'] !== $row['value'],
            isset($options['style']) ? 'style="' . $options['style'] . '"' : '',
            $row
        );
    @endphp
    @error('tv'.$field)
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="template">Шаблон записей</label>
    <select class="form-control" id="template" name="template">
        @foreach($templates as $item)
            <option @if(old('template', isset($matrix) ? $matrix->site_content_template : '') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->id . ' - ' . $item->templatename }}</option>
        @endforeach
    </select>
    @error('template')
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="parent_id">ID родительского документа</label>
    <input type="number" class="form-control" id="parent_id" name="parent_id" value="{{ old('parent_id', isset($matrix) ? $matrix->site_content_parent_id : '0') }}">
    @error('parent_id')
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

<fieldset>
    <legend>Шаблонизация</legend>
    <div class="form-group">
        <label for="pagetitle_template">Заголовок</label>
        <input type="text" class="form-control" id="pagetitle_template" name="pagetitle_template" value="{{ old('pagetitle_template', isset($matrix) ? $matrix->pagetitle_template : '') }}" placeholder="$category->pagetitle . ' ' . $entities[0]->pagetitle . ' в ' . $entities[1]->prepositional">
        @error('pagetitle_template')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="content">Контент</label>
        <textarea class="form-control" id="content" name="content" rows="5">{{ old('content', isset($matrix) ? $matrix->site_content_content : '') }}</textarea>
        @error('content')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="pagetitle_categories_tvlist">tvList - Категория</label>
        <input type="text" class="form-control" id="pagetitle_categories_tvlist" name="pagetitle_categories_tvlist" value="{{ old('pagetitle_categories_tvlist', isset($matrix) ? $matrix->pagetitle_categories_tvlist : '') }}" placeholder="genitive,prepositional">
        @error('pagetitle_categories_tvlist')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="pagetitle_entities_tvlist">tvList - Сущности</label>
        <input type="text" class="form-control" id="pagetitle_entities_tvlist" name="pagetitle_entities_tvlist" value="{{ old('pagetitle_entities_tvlist', isset($matrix) ? $matrix->pagetitle_entities_tvlist : '') }}" placeholder="genitive,prepositional">
        @error('pagetitle_entities_tvlist')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
</fieldset>



{{--@php--}}
{{--    $richtextparams = [--}}
{{--        'editor'   => evo()->getconfig('which_editor'),--}}
{{--        'elements' => 'tv'.$field,--}}
{{--    ];--}}
{{--    $richtextinit = evo()->invokeEvent('OnRichTextEditorInit', $richtextparams);--}}

{{--    foreach ($richtextinit as $r) {--}}
{{--        echo $r;--}}
{{--    }--}}

{{--    $field = isset($matrix) ? $matrix->id . 'content' : 'content';--}}
{{--    $value = '';--}}
{{--    $options = array (--}}
{{--      'caption' => 'Контент',--}}
{{--      'type' => 'richtext',--}}
{{--      'elements' => '',--}}
{{--      'default_text' => '',--}}
{{--    );--}}
{{--    $row = [--}}
{{--        'type'         => $options['type'],--}}
{{--        'name'         => $field,--}}
{{--        'caption'      => $options['caption'],--}}
{{--        'id'           => $field,--}}
{{--        'default_text' => '',--}}
{{--        'value'        => old('tv'.$field, $value),--}}
{{--        'elements'     => '',--}}
{{--    ];--}}
{{--    echo renderFormElement(--}}
{{--        $row['type'],--}}
{{--        $row['name'],--}}
{{--        '',--}}
{{--        $row['elements'],--}}
{{--        $row['value'] !== $row['value'],--}}
{{--        isset($options['style']) ? 'style="' . $options['style'] . '"' : '',--}}
{{--        $row--}}
{{--    );--}}
{{--@endphp--}}


<button class="btn btn-success mt-2" type="submit">Отправить</button>
