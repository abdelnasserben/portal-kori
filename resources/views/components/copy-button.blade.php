@props(['value'])

<button type="button"
        class="btn btn-link btn-sm p-0 ms-2 align-baseline"
        style="text-decoration:none;"
        data-copy-value="{{ $value }}"
        title="Copier">
    copier
</button>