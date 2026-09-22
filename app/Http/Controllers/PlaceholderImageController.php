<?php

namespace App\Http\Controllers;

use App\Models\Colour;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PlaceholderImageController extends Controller
{
    /**
     * Serve clearly-labelled development placeholder imagery for seeded products.
     * Replace ProductImage.path values with real photography paths in production.
     */
    public function __invoke(Request $request, string $name): Response
    {
        $parts = explode('-', $name, 2);
        $product = $parts[0] ? Product::query()->where('slug', $parts[0])->first() : null;
        $index = isset($parts[1]) ? (int) filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT) : 1;

        $hex = $request->string('c')->trim()->value();
        $colour = $hex ? Colour::query()->where('slug', $hex)->first() : null;
        $hex = $colour?->hex ?? '#dfdcd5';

        $label = $product?->name ?? 'Human In Motion';
        $title = 'DEMO IMAGERY — REPLACE F1';
        $seed = md5($name . $request->input('c', ''));

        $from = '#' . substr($seed, 0, 6);
        $to = $this->shade($hex, -18);
        $from = $this->shade($hex, 12);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="1200" viewBox="0 0 900 1200">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$from}"/>
      <stop offset="1" stop-color="{$to}"/>
    </linearGradient>
  </defs>
  <rect width="900" height="1200" fill="url(#g)"/>
  <g opacity="0.06" transform="rotate(-18 450 600)">
    <rect x="60" y="60" width="780" height="1080" fill="none" stroke="#0a0a0a" stroke-width="3"/>
    <rect x="90" y="90" width="720" height="1020" fill="none" stroke="#0a0a0a" stroke-width="1.5"/>
  </g>
  <text x="450" y="520" text-anchor="middle" font-family="Arial Black, Arial, sans-serif" font-size="64" font-weight="900" letter-spacing="18" fill="#0a0a0a" opacity="0.82">HUMAN IN MOTION</text>
  <text x="450" y="580" text-anchor="middle" font-family="Arial, sans-serif" font-size="28" letter-spacing="8" fill="#0a0a0a" opacity="0.6">R E P L A C E   I M A G E</text>
  <text x="450" y="880" text-anchor="middle" font-family="Arial, sans-serif" font-size="30" fill="#0a0a0a" opacity="0.5">{$label}</text>
  <g font-family="Arial, sans-serif" font-size="20" fill="#0a0a0a" letter-spacing="4">
    <text x="60" y="80" opacity="0.55">{$title}</text>
    <text x="60" y="110" opacity="0.45">Frame {$index}</text>
  </g>
  <text x="450" y="1150" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" letter-spacing="6" fill="#0a0a0a" opacity="0.4">DEV PLACEHOLDER — NOT FINAL PHOTOGRAPHY</text>
</svg>
SVG;

        return response($svg, 200, ['Content-Type' => 'image/svg+xml', 'Cache-Control' => 'public, max-age=86400']);
    }

    private function shade(string $hex, int $delta): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) {
            return '#0a0a0a';
        }
        [$r, $g, $b] = array_map(fn ($c) => max(0, min(255, hexdec($c) + $delta)), str_split($hex, 2));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}