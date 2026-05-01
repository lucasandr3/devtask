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

<div class="data-table-wrapper">
    {{-- Controls: Search + Filters + Create Button --}}
    <div class="data-table-controls">
        <div class="data-table-search">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input 
                type="text" 
                placeholder="{{ $searchPlaceholder }}" 
                id="{{ $tableId }}Search"
                class="data-table-search-input"
                data-table-id="{{ $tableId }}"
            >
        </div>
        
        <div class="data-table-actions">
            @if(count($filters) > 0)
                <div class="data-table-filters">
                    @foreach($filters as $key => $label)
                        <a 
                            href="{{ request()->fullUrlWithQuery([$filterParam => $key === 'todos' ? null : $key]) }}"
                            class="filter-btn {{ (request($filterParam, 'todos') === $key || (request($filterParam) === null && $key === 'todos')) ? 'active' : '' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if($createRoute)
                <a href="{{ $createRoute }}" class="btn-primary btn-responsive" title="{{ $createLabel }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="btn-text">{{ $createLabel }}</span>
                </a>
            @endif

            {{ $actions ?? '' }}
        </div>
    </div>

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

        {{-- Pagination --}}
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
    // Search functionality for all data tables
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
