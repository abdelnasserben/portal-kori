<div class="panel p-0 overflow-hidden">
    <div class="table-responsive">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="table-footer d-flex align-items-center justify-content-between gap-3 flex-wrap">
            {{ $footer }}
        </div>
    @endisset
</div>
