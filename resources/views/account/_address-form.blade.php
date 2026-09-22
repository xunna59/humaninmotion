@props(['address' => null, 'prefix' => ''])

<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="label" for="{{ $prefix }}a_name">FULL NAME</label>
        <input id="{{ $prefix }}a_name" name="name" value="{{ old('name', $address->name ?? '') }}" required class="field">
    </div>
    <div class="sm:col-span-2">
        <label class="label" for="{{ $prefix }}a_line_one">ADDRESS LINE 1</label>
        <input id="{{ $prefix }}a_line_one" name="line_one" value="{{ old('line_one', $address->line_one ?? '') }}" required class="field">
    </div>
    <div class="sm:col-span-2">
        <label class="label" for="{{ $prefix }}a_line_two">ADDRESS LINE 2 (OPTIONAL)</label>
        <input id="{{ $prefix }}a_line_two" name="line_two" value="{{ old('line_two', $address->line_two ?? '') }}" class="field">
    </div>
    <div>
        <label class="label" for="{{ $prefix }}a_city">CITY</label>
        <input id="{{ $prefix }}a_city" name="city" value="{{ old('city', $address->city ?? '') }}" required class="field">
    </div>
    <div>
        <label class="label" for="{{ $prefix }}a_county">COUNTY (OPTIONAL)</label>
        <input id="{{ $prefix }}a_county" name="county" value="{{ old('county', $address->county ?? '') }}" class="field">
    </div>
    <div>
        <label class="label" for="{{ $prefix }}a_postcode">POSTCODE</label>
        <input id="{{ $prefix }}a_postcode" name="postcode" value="{{ old('postcode', $address->postcode ?? '') }}" required class="field">
    </div>
    <div>
        <label class="label" for="{{ $prefix }}a_country">COUNTRY</label>
        <input id="{{ $prefix }}a_country" name="country" value="{{ old('country', $address->country ?? 'United Kingdom') }}" required class="field">
    </div>
    <div class="sm:col-span-2">
        <label class="label" for="{{ $prefix }}a_phone">PHONE (OPTIONAL)</label>
        <input id="{{ $prefix }}a_phone" name="phone" type="tel" value="{{ old('phone', $address->phone ?? '') }}" class="field">
    </div>
</div>