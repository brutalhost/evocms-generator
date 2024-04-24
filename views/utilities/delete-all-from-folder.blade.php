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
                <button type="submit" class="btn btn-danger">Удалить дочерние документы</button>
            </form>
        </div>
    </div>
</div>


