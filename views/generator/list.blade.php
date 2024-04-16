@php use EvolutionCMS\Models\SiteTemplate; @endphp
<h2>Список матриц</h2>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <th scope="col">#</th>
        <th scope="col">Заголовок</th>
        <th scope="col">Шаблон</th>
        <th scope="col">ID родителя</th>
        <th scope="col">Последнее обновление</th>
        <th scope="col">Действия</th>
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
            <td>{{ $item->updated_at->diffForHumans() }}</td>
            <td>
                <div class="d-flex" style="gap: 0.15rem;">
                    <button
                            hx-get="{{ route('generator::matrix.getmodal.edit', $item) }}"
                            hx-target="#modals-here"
                            hx-trigger="click"
                            data-toggle="modal"
                            data-target="#modals-here"
                            data-tooltip="Редактировать"
                            class="btn btn-info">
                        <i class="fa fa-edit"></i>
                    </button>
                    <form action="{{ route('generator::matrix.delete', $item) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger" data-tooltip="Удалить"><i class="fa fa-trash-alt"></i></button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>