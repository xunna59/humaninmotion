@if (session('status'))
    <div class="mb-6 border border-ok/30 bg-ok/5 text-ok px-4 py-3 text-sm">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 border border-sale/30 bg-sale/5 text-sale px-4 py-3 text-sm">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif