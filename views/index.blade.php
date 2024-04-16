@extends('generator::layouts.app')

@section('buttons')
    <div id="actions">
        <div class="btn-group">
            <a href="javascript:;" class="btn btn-success" onclick="location.reload();">
                <i class="fa fa-refresh"></i><span>Обновить</span>
            </a>
        </div>
    </div>
@endsection

@section('body')
    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="tab-page" id="tab_main">
        <h2 class="tab">
            Матрицы
        </h2>
        @include('generator::generator.generator')
    </div>

    <div class="tab-page" id="tab_processes">
        <h2 class="tab">
            Обработчики
        </h2>
        @include('generator::processes.processes')
    </div>

    <div class="tab-page" id="tab_utilities">
        <h2 class="tab">
            Утилиты
        </h2>
        @include('generator::utilities.utilities')
    </div>

    <div class="tab-page" id="tab_help">
        <h2 class="tab">
            Помощь
        </h2>
        @include('generator::help.help')
    </div>

    <script type="text/javascript">
        tpModule.addTabPage(document.getElementById("tab_main"));
        tpModule.addTabPage(document.getElementById("tab_queues"));
        tpModule.addTabPage(document.getElementById("tab_utilities"));
        tpModule.addTabPage(document.getElementById("tab_help"));
    </script>
@endsection

