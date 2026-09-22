@props(['crumbs' => [], 'current' => null])

<nav aria-label="Breadcrumb" class="py-4 border-b border-ink/10">
    <ol class="container-site flex items-center gap-2 text-[11px] uppercase tracking-wider text-graphite">
        <li>
            <a href="{{ route('home') }}" class="hover:text-ink transition-colors">Home</a>
        </li>
        @foreach ($crumbs as $crumb)
            <li class="flex items-center gap-2">
                <span aria-hidden="true">/</span>
                <a href="{{ $crumb['url'] }}" class="hover:text-ink transition-colors">{{ $crumb['label'] }}</a>
            </li>
        @endforeach
        @if ($current)
            <li class="flex items-center gap-2">
                <span aria-hidden="true">/</span>
                <span class="text-ink font-medium">{{ $current }}</span>
            </li>
        @endif
    </ol>
</nav>