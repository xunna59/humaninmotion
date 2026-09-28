@props(['class' => 'max-h-9 lg:max-h-10 max-w-[150px] sm:max-w-[190px] lg:max-w-[230px]'])

@inject('site', 'App\Services\SettingsService')

@if ($site->logoUrl())
    <img src="{{ $site->logoUrl() }}" alt="Human In Motion" loading="lazy"
         class="h-auto w-auto object-contain {{ $class }}">
@else
    {{ $slot }}
@endif