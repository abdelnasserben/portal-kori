@props(['routeName', 'filters' => [], 'queryPlaceholder' => 'Ref, code, ...', 'statusOptions' => []])

<x-filters-bar>
    <form method="GET" action="{{ route($routeName) }}">
        <div class="row g-2">
            <div class="col-12 col-md-4">
                <x-form.input name="query" label="Search" :value="$filters['query'] ?? ''" :placeholder="$queryPlaceholder"
                    class="form-control-sm" />
            </div>

            <div class="col-6 col-md-2">
                <x-form.select name="status" label="Status" :value="$filters['status'] ?? ''" :options="$statusOptions" placeholder="All"
                    class="form-select-sm" />
            </div>

            <div class="col-6 col-md-2">
                <x-form.input name="createdFrom" label="Created from" type="date" :value="$filters['createdFrom'] ?? ''"
                    class="form-control-sm" />
            </div>

            <div class="col-6 col-md-2">
                <x-form.input name="createdTo" label="Created to" type="date" :value="$filters['createdTo'] ?? ''"
                    class="form-control-sm" />
            </div>

            <div class="col-6 col-md-1">
                <x-form.input name="limit" label="Limit" type="number" :value="$filters['limit'] ?? 25" min="1" max="200"
                    class="form-control-sm" />
            </div>

            <div class="col-6 col-md-3">
                <x-form.input name="sort" label="Sort" :value="$filters['sort'] ?? ''" placeholder="createdAt:desc"
                    class="form-control-sm" />
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-sm btn-primary" type="submit">Apply filters</button>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route($routeName) }}">Clear</a>
            </div>
        </div>
    </form>
</x-filters-bar>
