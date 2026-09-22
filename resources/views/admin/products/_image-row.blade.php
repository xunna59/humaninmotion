<div class="image-row card p-3 grid sm:grid-cols-[1fr_1fr_80px_auto] items-end gap-3">
    <div>
        <label class="label">Image URL</label>
        <input class="input" type="text" name="{{ $id ? 'images[' . $id . '][path]' : 'images[new_0][path]' }}" value="{{ $row['path'] }}" placeholder="/placeholder/product-x-1.svg">
    </div>
    <div>
        <label class="label">Alt text</label>
        <input class="input" type="text" name="{{ $id ? 'images[' . $id . '][alt_text]' : 'images[new_0][alt_text]' }}" value="{{ $row['alt_text'] }}">
    </div>
    <div>
        <label class="label">Position</label>
        <input class="input" type="number" min="1" name="{{ $id ? 'images[' . $id . '][position]' : 'images[new_0][position]' }}" value="{{ $row['position'] }}">
    </div>
    <div class="flex items-center gap-2">
        <img src="{{ \Illuminate\Support\Str::startsWith($row['path'], ['http', '/']) ? $row['path'] : '/' . $row['path'] }}"
             alt="" class="w-12 h-12 object-cover border border-ink/10 bg-warmgray">
        <input type="hidden" name="{{ $id ? 'images[' . $id . '][id]' : 'images[new_0][id]' }}" value="{{ $row['id'] }}">
        <button type="button" class="remove-row text-sale text-xs underline underline-offset-4">Remove</button>
    </div>
</div>