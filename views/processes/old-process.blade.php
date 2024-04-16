@if($processList != [])
    <hr>
    <h2>Запущенные процессы</h2>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Действие</th>
            </tr>
            </thead>
            <tbody>
            @foreach($processList as $item)
            <tr>
                <td>{{ $item['id'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>
                    <form action="{{ route("generator::command.killprocess", $item['id']) }}" method="get">
                        @csrf
                        <button class="btn btn-danger" type="submit">Убить процесс</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
