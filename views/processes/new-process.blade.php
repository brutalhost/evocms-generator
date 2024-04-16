@php use EvolutionCMS\Generator\Services\Combinatorics;use Illuminate\Support\Facades\DB; @endphp
<h2>Список обработчиков</h2>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Название</th>
            <th>Шаблон записей</th>
            <th>Родительская папка</th>
            <th>Будет сгенерировано</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($matrices as $item)
            <tr>
                <th scope="row">{{ $item->id }}</th>
                <td>
                    {{ $item->table_folders ?? '' }}
                </td>
                <td>
                    {{ $item->table_template ?? '' }}
                </td>
                <td>
                    {{ $item->table_parent ?? '' }}
                </td>
                <td>{{ $item->table_articles_count ?? '' }}</td>
                <td>
                    <div class="d-flex" style="gap: 0.15rem;">
                        <button
                                hx-get="{{ route('generator::matrix.getmodal.edit', $item) }}"
                                hx-target="#modals-here"
                                hx-trigger="click"
                                data-toggle="modal"
                                data-target="#modals-here"
                                data-tooltip="Редактировать"
                                class="btn"><i class="fa fa-edit"></i>
                        </button>

                        <button
                                hx-get="{{ route('generator::command.onearticle', ['matrix' => $item, 'option' => 'preview']) }}"
                                hx-target="#modals-here"
                                hx-trigger="click"
                                data-toggle="modal"
                                data-target="#modals-here"
                                data-tooltip="Посмотреть в модалке"
                                @if($item->table_articles_count == 0) disabled @endif
                                class="btn"><i class="fa fa-eye"></i>
                        </button>
                        {{--                    <form action="{{ route("generator::command.onearticle", $item) }}" method="get">--}}
                        {{--                        <button type="submit" class="btn" @if($item->table_articles_count == 0) disabled @endif>Сгенерировать тестовую запись</button>--}}
                        {{--                    </form>--}}
                        <form action="{{ route('generator::command.onearticle', ['matrix' => $item, 'option' => 'one']) }}" method="get">
                            <button type="submit" class="btn" @if($item->table_articles_count == 0) disabled @endif>Сгенерировать одну запись</button>
                        </form>
                        <form action="{{ route("generator::command.allarticles", $item) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success" @if($item->table_articles_count == 0) disabled @endif>Запустить генерацию</button>
                        </form>
                    </div>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>