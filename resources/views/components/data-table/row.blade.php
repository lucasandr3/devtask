@props(['selectable' => false])

<tr {{ $attributes->merge(['class' => 'data-table-row']) }}>
    @if($selectable)
        <td class="data-table-td data-table-td-checkbox">
            <input type="checkbox" class="data-table-checkbox">
        </td>
    @endif
    {{ $slot }}
</tr>
