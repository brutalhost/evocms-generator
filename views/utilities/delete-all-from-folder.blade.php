<div class="row" style="row-gap: 0.75rem; column-gap: 0;">
    <div class="col col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="card-title">Удаление дочерних документов</h3>
                <h6 class="card-subtitle mb-2 text-muted">Укажите ID родительского документа и скрипт удалит всех его
                    потомков</h6>
                <form action="{{ route('generator::utilities.deletechildrens') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="util-delete-all_parent_id">ID родительского документа</label>
                        <input type="number" class="form-control" id="util-delete-all_parent_id"
                               name="util-delete-all_parent_id" value="{{ old('util-delete-all_parent_id','') }}">
                        @error('util-delete-all_parent_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Отправить</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h3 class="card-title">Массовое добавление сущностей</h3>
                <h6 class="card-subtitle mb-2 text-muted">Скрипт создаёт документы с заданными pagetitle в указанной
                    папке с выбранным шаблоном</h6>
                <form action="{{ route('generator::utilities.massadd') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="util-mass-add_parent_id">ID родительского документа</label>
                        <input type="number" class="form-control" id="util-mass-add_parent_id"
                               name="util-mass-add_parent_id" value="{{ old('util-mass-add_parent_id','') }}">
                        @error('util-mass-add_parent_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="util-mass-add_template">Шаблон записей</label>
                        <select class="form-control" id="util-mass-add_template" name="util-mass-add_template">
                            @foreach($templates as $item)
                                <option @if(old('util-mass-add_template', '') == $item->id) selected
                                        @endif value="{{ $item->id }}">{{ $item->id . ' - ' . $item->templatename }}</option>
                            @endforeach
                        </select>
                        @error('util-mass-add_template')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="entfolders">Категории</label>
                        @php
                            $field = 'dscategories';
                            $value = '';
                            $options = array (
                              'caption' => 'Категории',
                              'type' => 'custom_tv:selector',
                              'note' => 'Список категорий',
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
                        @error('tvdscategories')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="util-mass-add_pagetitles">Pagetitle для каждого документа с новой строки</label>
                        <textarea class="form-control" id="util-mass-add_pagetitles" name="util-mass-add_pagetitles"
                                  rows="5">{{ old('util-mass-add_pagetitles','') }}</textarea>
                        @error('util-mass-add_pagetitles')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Отправить</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col"></div>
</div>
