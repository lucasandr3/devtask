@props([
    'title' => '',
    'createRoute' => null,
    'createLabel' => 'Novo',
    'searchPlaceholder' => 'Pesquisar...',
    'searchParam' => 'search',
    'filters' => [],
    'filterParam' => 'status',
    'currentFilter' => 'todos',
    'columns' => [],
    'emptyIcon' => null,
    'emptyMessage' => 'Nenhum registro encontrado',
    'emptyCreateLabel' => 'Criar primeiro registro',
    'selectable' => false,
    'tableId' => 'dataTable',
])

@php
$hasToolbarTop = (isset($toolbarLeading) && !$toolbarLeading->isEmpty()) || (isset($toolbarTrailing) && !$toolbarTrailing->isEmpty());
$hasFilters = count($filters) > 0;
@endphp

<div class="data-table-wrapper">
    <x-ui.page-toolbar>
        @if($hasToolbarTop)
            <x-slot:leading>{{ $toolbarLeading ?? '' }}</x-slot:leading>
            <x-slot:trailing>{{ $toolbarTrailing ?? '' }}</x-slot:trailing>
        @endif

        <x-slot:bottom>
            <div class="page-toolbar-bottom-inner">
                <x-ui.search-input
                    :placeholder="$searchPlaceholder"
                    :table-id="$tableId"
                    class="w-full sm:max-w-xs"
                />

                <div class="page-toolbar-bottom-actions">
                    @if($hasFilters)
                        <div class="filter-tabs">
                            @foreach($filters as $key => $label)
                                <a
                                    href="{{ request()->fullUrlWithQuery([$filterParam => $key === 'todos' ? null : $key]) }}"
                                    class="filter-tab {{ (request($filterParam, 'todos') === $key || (request($filterParam) === null && $key === 'todos')) ? 'filter-tab-active' : '' }}"
                                >
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{ $actions ?? '' }}

                    @if($createRoute && !$hasToolbarTop)
                        <a href="{{ $createRoute }}" class="btn-primary h-9 px-3 shrink-0 ui-tooltip ui-tooltip-top" data-tooltip="{{ $createLabel }}" aria-label="{{ $createLabel }}">
                            <x-ui.icon name="plus" />
                            <span class="hidden sm:inline">{{ $createLabel }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </x-slot:bottom>
    </x-ui.page-toolbar>

    {{-- Table Card --}}
    <div class="card data-table-card">
        <div class="data-table-scroll">
            <table class="data-table" id="{{ $tableId }}">
                <thead class="data-table-header">
                    <tr>
                        @if($selectable)
                            <th class="data-table-th data-table-th-checkbox">
                                <input type="checkbox" class="data-table-checkbox" onclick="toggleSelectAll(this, '{{ $tableId }}')">
                            </th>
                        @endif
                        {{ $head }}
                    </tr>
                </thead>
                <tbody class="data-table-body" id="{{ $tableId }}Body">
                    {{ $slot }}
                </tbody>
            </table>
        </div>

        @if(isset($pagination) && trim($pagination) !== '')
            <div class="data-table-pagination">
                {{ $pagination }}
            </div>
        @endif
    </div>
</div>

@once
@push('scripts')
<script>
function toggleSelectAll(checkbox, tableId) {
    const table = document.getElementById(tableId);
    const checkboxes = table.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-table-id]').forEach(function(searchInput) {
        searchInput.addEventListener('input', function(e) {
            const tableId = this.getAttribute('data-table-id');
            const searchTerm = e.target.value.toLowerCase();
            const tableBody = document.getElementById(tableId + 'Body');

            if (!tableBody) return;

            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
});
</script>
@endpush
@endonce
