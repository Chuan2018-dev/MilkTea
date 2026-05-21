<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ProductImageController extends Controller
{
    public function show(Product $product): Response
    {
        $style = $this->styleFor($product);
        $title = $this->escape($product->name);
        $lines = $this->labelLines($product->name);
        $label = '';

        foreach ($lines as $index => $line) {
            $y = 508 + ($index * 36);
            $label .= '<text x="400" y="' . $y . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="30" font-weight="700" fill="' . $style['text'] . '">' . $this->escape($line) . '</text>';
        }

        $garnish = $this->garnish($style);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" role="img" aria-labelledby="title desc">
  <title id="title">{$title}</title>
  <desc id="desc">Illustrated product image for {$title}</desc>
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$style['bg1']}"/>
      <stop offset="100%" stop-color="{$style['bg2']}"/>
    </linearGradient>
    <linearGradient id="drink" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="{$style['foam']}"/>
      <stop offset="30%" stop-color="{$style['drink']}"/>
      <stop offset="100%" stop-color="{$style['deep']}"/>
    </linearGradient>
    <clipPath id="cupClip">
      <path d="M246 164h308l-36 292c-4 32-31 56-64 56H346c-33 0-60-24-64-56L246 164z"/>
    </clipPath>
    <filter id="softShadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="18" stdDeviation="18" flood-color="#2d1a11" flood-opacity=".22"/>
    </filter>
  </defs>

  <rect width="800" height="600" rx="0" fill="url(#bg)"/>
  <circle cx="118" cy="110" r="74" fill="#fff" opacity=".20"/>
  <circle cx="684" cy="135" r="98" fill="#fff" opacity=".17"/>
  <circle cx="112" cy="473" r="46" fill="#fff" opacity=".18"/>
  <circle cx="675" cy="478" r="58" fill="{$style['accent']}" opacity=".12"/>

  <ellipse cx="400" cy="534" rx="194" ry="30" fill="#28180f" opacity=".20"/>
  <g filter="url(#softShadow)">
    <path d="M240 144h320c14 0 26 12 26 26v8c0 14-12 26-26 26H240c-14 0-26-12-26-26v-8c0-14 12-26 26-26z" fill="#fff8ef"/>
    <path d="M246 164h308l-36 292c-4 32-31 56-64 56H346c-33 0-60-24-64-56L246 164z" fill="#fff" opacity=".72"/>
    <g clip-path="url(#cupClip)">
      <rect x="224" y="172" width="352" height="340" fill="url(#drink)"/>
      <path d="M226 190c45 22 91 22 137 0s93-22 139 0 68 21 76 16v54H226z" fill="#fff" opacity=".24"/>
      <path d="M226 242c38 16 78 16 118 0s80-17 121 0 79 17 113 3v44H226z" fill="{$style['accent']}" opacity=".18"/>
      {$garnish}
      <path d="M322 170h44l-28 334h-44z" fill="#fff" opacity=".20"/>
    </g>
    <path d="M246 164h308l-36 292c-4 32-31 56-64 56H346c-33 0-60-24-64-56L246 164z" fill="none" stroke="#fffaf2" stroke-width="10" opacity=".82"/>
    <path d="M265 152h270" stroke="#6b4423" stroke-width="18" stroke-linecap="round" opacity=".16"/>
    <path d="M490 94l32-12 24 112" stroke="#ffffff" stroke-width="17" stroke-linecap="round" opacity=".88"/>
    <path d="M490 94l32-12 24 112" stroke="{$style['accent']}" stroke-width="7" stroke-linecap="round" opacity=".78"/>
  </g>

  <g opacity=".95">
    {$label}
    <text x="400" y="570" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="700" fill="{$style['text']}" opacity=".72">Milk Tea Shop</text>
  </g>
</svg>
SVG;

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    private function styleFor(Product $product): array
    {
        $name = Str::lower($product->name);

        $styles = [
            'wintermelon' => ['bg1' => '#eff7d3', 'bg2' => '#b8d98f', 'drink' => '#9aba5a', 'deep' => '#6f8f35', 'foam' => '#f5f1c9', 'accent' => '#55752a', 'text' => '#2e4d19', 'garnish' => 'melon'],
            'okinawa' => ['bg1' => '#f8dfba', 'bg2' => '#c57a3a', 'drink' => '#a85d2b', 'deep' => '#623316', 'foam' => '#f4d39a', 'accent' => '#5c2d13', 'text' => '#44200e', 'garnish' => 'brown-sugar'],
            'thai' => ['bg1' => '#ffe0b8', 'bg2' => '#f28c28', 'drink' => '#f07d20', 'deep' => '#a94911', 'foam' => '#ffd39a', 'accent' => '#a54312', 'text' => '#5a250c', 'garnish' => 'thai'],
            'taro' => ['bg1' => '#eadbff', 'bg2' => '#b68adf', 'drink' => '#a879d3', 'deep' => '#74419b', 'foam' => '#f4eaff', 'accent' => '#74419b', 'text' => '#43245c', 'garnish' => 'taro'],
            'matcha' => ['bg1' => '#dff5d4', 'bg2' => '#82bd68', 'drink' => '#6ca94f', 'deep' => '#376f2f', 'foam' => '#e8f6cf', 'accent' => '#2f6b2a', 'text' => '#224d1f', 'garnish' => 'matcha'],
            'strawberry' => ['bg1' => '#ffe0e5', 'bg2' => '#f0627e', 'drink' => '#f46f87', 'deep' => '#b52e4a', 'foam' => '#ffe9ee', 'accent' => '#b52e4a', 'text' => '#6b1729', 'garnish' => 'strawberry'],
            'mango' => ['bg1' => '#fff1bc', 'bg2' => '#f7b733', 'drink' => '#f5ac27', 'deep' => '#c87300', 'foam' => '#fff2b5', 'accent' => '#b76500', 'text' => '#653900', 'garnish' => 'mango'],
            'passion' => ['bg1' => '#fff0a8', 'bg2' => '#f6c13d', 'drink' => '#f2bc31', 'deep' => '#ca8122', 'foam' => '#fff3be', 'accent' => '#7f4b18', 'text' => '#59300c', 'garnish' => 'passion'],
            'americano' => ['bg1' => '#ddd6ce', 'bg2' => '#7b6659', 'drink' => '#3c2a20', 'deep' => '#1d120d', 'foam' => '#72513d', 'accent' => '#24140d', 'text' => '#20110b', 'garnish' => 'coffee'],
            'caramel' => ['bg1' => '#ffe4bd', 'bg2' => '#c8863c', 'drink' => '#c88a52', 'deep' => '#70401d', 'foam' => '#ffe0a6', 'accent' => '#8f4f1d', 'text' => '#4c260d', 'garnish' => 'caramel'],
            'hazelnut' => ['bg1' => '#ead6bd', 'bg2' => '#a06b42', 'drink' => '#b17a4c', 'deep' => '#5d3218', 'foam' => '#f4dfc3', 'accent' => '#6b381c', 'text' => '#3b1d0d', 'garnish' => 'hazelnut'],
        ];

        foreach ($styles as $keyword => $style) {
            if (Str::contains($name, $keyword)) {
                return $style;
            }
        }

        return ['bg1' => '#f2dfc7', 'bg2' => '#b88957', 'drink' => '#9a6639', 'deep' => '#5f351b', 'foam' => '#efd3ab', 'accent' => '#6b4423', 'text' => '#3b2414', 'garnish' => 'pearls'];
    }

    private function garnish(array $style): string
    {
        return match ($style['garnish']) {
            'melon' => '<circle cx="338" cy="394" r="18" fill="#dff0a8" opacity=".95"/><circle cx="390" cy="430" r="18" fill="#dff0a8" opacity=".95"/><circle cx="452" cy="394" r="18" fill="#dff0a8" opacity=".95"/><path d="M300 315c40-30 87-30 127 0" stroke="#eef8c6" stroke-width="13" stroke-linecap="round" opacity=".75"/>',
            'brown-sugar' => '<path d="M308 190c-8 52 45 75 20 127s26 81 10 137" stroke="#4a210d" stroke-width="18" stroke-linecap="round" opacity=".48"/><path d="M462 188c22 58-40 82-15 135s-24 75-5 128" stroke="#4a210d" stroke-width="16" stroke-linecap="round" opacity=".42"/>',
            'thai' => '<path d="M334 394l22-16 22 16-9 26h-26z" fill="#7a2d12" opacity=".8"/><path d="M450 310l20-14 20 14-8 23h-24z" fill="#7a2d12" opacity=".55"/><circle cx="392" cy="418" r="16" fill="#ffc36a" opacity=".9"/>',
            'taro' => '<rect x="316" y="378" width="36" height="36" rx="9" fill="#e6c9ff" opacity=".95"/><rect x="386" y="418" width="34" height="34" rx="9" fill="#e6c9ff" opacity=".95"/><rect x="458" y="378" width="36" height="36" rx="9" fill="#e6c9ff" opacity=".95"/><circle cx="390" cy="324" r="22" fill="#f4eaff" opacity=".55"/>',
            'matcha' => '<path d="M332 345c64-46 112-29 148 4-54-4-89 15-126 48" fill="#dff5d4" opacity=".72"/><path d="M350 383c42-44 87-56 129-33" stroke="#2f6b2a" stroke-width="8" stroke-linecap="round" opacity=".55"/>',
            'strawberry' => '<path d="M330 388c27-28 64-6 52 31-13 41-70 31-66-10 1-8 6-16 14-21z" fill="#ff3158" opacity=".9"/><path d="M448 326c25-26 58-5 47 29-12 38-64 28-60-9 1-8 6-15 13-20z" fill="#ff3158" opacity=".78"/><circle cx="345" cy="401" r="3" fill="#ffe2a7"/><circle cx="359" cy="419" r="3" fill="#ffe2a7"/><circle cx="459" cy="339" r="3" fill="#ffe2a7"/>',
            'mango' => '<path d="M316 382c36-45 92-34 112 3-35 32-87 35-112-3z" fill="#ffd23f" opacity=".92"/><path d="M440 322c30-35 74-27 91 2-30 26-70 28-91-2z" fill="#ffd23f" opacity=".76"/><path d="M340 386c22-14 43-16 67-4" stroke="#ee8b00" stroke-width="8" stroke-linecap="round" opacity=".55"/>',
            'passion' => '<circle cx="344" cy="384" r="34" fill="#fff06d" opacity=".84"/><circle cx="454" cy="354" r="31" fill="#fff06d" opacity=".7"/><circle cx="333" cy="376" r="4" fill="#5a2a10"/><circle cx="352" cy="395" r="4" fill="#5a2a10"/><circle cx="455" cy="354" r="4" fill="#5a2a10"/><circle cx="468" cy="369" r="4" fill="#5a2a10"/>',
            'coffee' => '<rect x="308" y="272" width="56" height="48" rx="10" fill="#dce8f3" opacity=".72" transform="rotate(-12 336 296)"/><rect x="438" y="338" width="56" height="48" rx="10" fill="#dce8f3" opacity=".60" transform="rotate(15 466 362)"/><path d="M350 425c25-28 53-13 56 10-18 21-44 19-56-10z" fill="#1d120d" opacity=".75"/>',
            'caramel' => '<path d="M296 202c74 50 129 34 208-3" stroke="#6f3410" stroke-width="14" stroke-linecap="round" opacity=".52"/><path d="M315 260c73 41 122 35 171-5" stroke="#ffd07e" stroke-width="11" stroke-linecap="round" opacity=".7"/><circle cx="400" cy="420" r="20" fill="#ffe0a6" opacity=".55"/>',
            'hazelnut' => '<ellipse cx="340" cy="396" rx="24" ry="18" fill="#5c2d13" opacity=".82" transform="rotate(-23 340 396)"/><ellipse cx="454" cy="358" rx="24" ry="18" fill="#5c2d13" opacity=".65" transform="rotate(19 454 358)"/><path d="M330 392c10 4 20 5 30 0" stroke="#d2a06d" stroke-width="5" stroke-linecap="round" opacity=".85"/>',
            default => '<circle cx="316" cy="434" r="18" fill="#2b1710" opacity=".82"/><circle cx="366" cy="462" r="18" fill="#2b1710" opacity=".82"/><circle cx="421" cy="436" r="18" fill="#2b1710" opacity=".82"/><circle cx="474" cy="464" r="18" fill="#2b1710" opacity=".82"/><circle cx="512" cy="424" r="18" fill="#2b1710" opacity=".82"/>',
        };
    }

    private function labelLines(string $name): array
    {
        if (mb_strlen($name) <= 24) {
            return [$name];
        }

        $words = explode(' ', $name);
        $lines = [''];

        foreach ($words as $word) {
            $current = trim(end($lines) . ' ' . $word);

            if (mb_strlen($current) > 24 && count($lines) < 2) {
                $lines[] = $word;
                continue;
            }

            $lines[array_key_last($lines)] = $current;
        }

        return array_filter($lines);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
