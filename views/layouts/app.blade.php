<?php include_once MODX_MANAGER_PATH . 'includes/header.inc.php' ?>


<script src="https://unpkg.com/htmx.org@1.9.11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

<style>
    .pagination {
        margin-inline: 0;
    }
    table {
        text-wrap: nowrap;
    }
</style>

{{--<meta name="htmx-config" content='{"globalViewTransitions":true}'>--}}

<div class="module-page">
    <h1>
        <i class="fa fa-hashtag"></i>
        @yield('pagetitle', 'DocShaker')
    </h1>

    @yield('buttons')

    <div class="sectionBody">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="tab-pane" id="documentPane">
            <script type="text/javascript">
                var tpModule = new WebFXTabPane(document.getElementById('documentPane'), true);
            </script>

            @yield('body')
        </div>
    </div>
</div>

<div id="modals-here"
     class="modal modal-blur fade"
     style="display: none"
     aria-hidden="false"
     tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content"></div>
    </div>
</div>

{{--<link rel="stylesheet" href="{{ MODX_BASE_URL }}assets/modules/example/css/style.css">--}}
@stack('scripts')

<?php include_once MODX_MANAGER_PATH . 'includes/footer.inc.php' ?>
