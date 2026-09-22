<section class="py-16 lg:py-24 bg-shell">
    <div class="container-site max-w-xl text-center">
        <p class="eyebrow text-brass mb-3">THE JOURNAL</p>
        <h2 class="display-campaign text-4xl">{{ $section->title ?? 'IN THE MOMENT' }}</h2>
        <p class="mt-4 text-ink/70">{{ $section->description }}</p>
        <form action="{{ route('newsletter.subscribe') }}" method="POST"
              class="mt-8 flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="email" name="email" required placeholder="YOUR EMAIL ADDRESS"
                   class="field flex-1" aria-label="Email address">
            <button type="submit" class="btn btn-primary justify-center shrink-0">SUBSCRIBE</button>
        </form>
        <p class="mt-4 text-xs text-graphite">Be first to new drops. No noise, ever.</p>
    </div>
</section>