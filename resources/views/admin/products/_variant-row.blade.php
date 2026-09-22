<tr class="border-b border-ink/10">
    <td class="py-2 pr-2">
        <input class="input py-2" type="text" name="{{ $prefix }}[size]" value="{{ $row['size'] }}" placeholder="M">
    </td>
    <td class="py-2 pr-2">
        <input class="input py-2" type="text" name="{{ $prefix }}[colour]" value="{{ $row['colour'] }}" placeholder="Black">
    </td>
    <td class="py-2 pr-2">
        <input class="input py-2" type="text" name="{{ $prefix }}[sku]" value="{{ $row['sku'] }}">
    </td>
    <td class="py-2 pr-2">
        <input class="input py-2" type="number" step="0.01" min="0" name="{{ $prefix }}[price]" value="{{ $row['price'] }}">
    </td>
    <td class="py-2 pr-2">
        <input class="input py-2" type="number" step="0.01" min="0" name="{{ $prefix }}[compare_price]" value="{{ $row['compare_price'] }}">
    </td>
    <td class="py-2 pr-2">
        <input class="input py-2" type="number" min="0" name="{{ $prefix }}[stock]" value="{{ $row['stock'] }}">
    </td>
    <td class="py-2 pr-2">
        <input class="input py-2" type="number" min="0" name="{{ $prefix }}[low_stock_threshold]" value="{{ $row['low_stock_threshold'] ?? 3 }}">
    </td>
    <td class="py-2 pr-2">
        <input type="checkbox" name="{{ $prefix }}[is_active]" value="1" class="checkbox" @checked($row['is_active'] ?? true)>
    </td>
    <td class="py-2">
        <input type="hidden" name="{{ $prefix }}[id]" value="{{ $row['id'] }}">
        <button type="button" class="remove-row text-sale text-xs underline underline-offset-4">Remove</button>
    </td>
</tr>