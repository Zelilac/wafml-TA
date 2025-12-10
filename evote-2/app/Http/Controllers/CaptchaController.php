<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    protected function makeCode($length = 6)
    {
        // huruf besar dan angka (bisa ganti ke lower atau mix)
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // hindari I, O, 1, 0 jika mau jelas
        $code = '';
        for ($i = 0; $i < $length; $i++)
        {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    public function image(Request $request)
    {
        $code = $this->makeCode(6);

        session(['captcha_code' => $code]);

        $w = 160;
        $h = 50;
        $bg = '#f3f4f6';
        $textColor = '#111827';
        $fontFamily = 'Arial, Helvetica, sans-serif';
        $fontSize = 30;

        // pusat untuk rotasi global (hitung dulu, jangan {$w/2})
        $cx = $w / 2;
        $cy = $h / 2;

        // NOISE: garis acak (background)
        $bgLines = '';
        $bgLineCount = 10;
        for ($i = 0; $i < $bgLineCount; $i++)
        {
            $x1 = random_int(0, $w);
            $y1 = random_int(0, $h);
            $x2 = random_int(0, $w);
            $y2 = random_int(0, $h);
            $strokeWidth = random_int(1, 2);
            // warna abu-abu terang random
            $strokeColor = sprintf(
                '#%02X%02X%02X',
                random_int(200, 235),
                random_int(200, 235),
                random_int(200, 235)
            );
            $bgLines .= "<line x1='{$x1}' y1='{$y1}' x2='{$x2}' y2='{$y2}' stroke='{$strokeColor}' stroke-width='{$strokeWidth}' opacity='0.9' />";
        }

        // NOISE: titik acak
        $dots = '';
        $dotCount = 80;
        for ($i = 0; $i < $dotCount; $i++)
        {
            $dx = random_int(0, $w);
            $dy = random_int(0, $h);
            $r  = random_int(1, 2);
            $fill = sprintf(
                '#%02X%02X%02X',
                random_int(210, 245),
                random_int(210, 245),
                random_int(210, 245)
            );
            $dots .= "<circle cx='{$dx}' cy='{$dy}' r='{$r}' fill='{$fill}' />";
        }

        // wavy line di belakang teks (pakai cubic bezier)
        $wy = random_int((int)($h * 0.35), (int)($h * 0.65));
        $c1x = random_int(20, 60);
        $c1y = random_int(0, $h);
        $c2x = random_int(80, 120);
        $c2y = random_int(0, $h);
        $c3x = random_int(140, 180);
        $c3y = random_int(0, $h);
        $wavyPath = "M 0 {$wy} C {$c1x} {$c1y}, {$c2x} {$c2y}, {$c3x} {$c3y}";
        // extend sampai kanan
        $wavyPath .= " S " . ($w + 20) . " " . random_int(0, $h) . ", " . ($w) . " " . random_int(0, $h);

        // huruf per-huruf: posisi & rotasi acak ringan
        $chars = mb_str_split($code);
        $charSvg = '';
        $count = count($chars);
        $leftPad = 12;
        $rightPad = 12;
        $usableWidth = $w - $leftPad - $rightPad;
        $baseSpacing = (int) floor($usableWidth / max(1, $count));
        $yBase = (int) ($h * 0.65);

        foreach ($chars as $i => $ch)
        {
            $xi = $leftPad + $i * $baseSpacing + random_int(-2, 6);
            $yi = $yBase + random_int(-5, 5);
            $rot = random_int(-22, 22);
            $charSvg .= "<text x='{$xi}' y='{$yi}' font-family='{$fontFamily}' font-size='{$fontSize}' fill='{$textColor}' transform='rotate({$rot} {$xi} {$yi})'>{$ch}</text>";
        }

        // garis coret di depan teks (foreground strike)
        $sy1 = random_int((int)($h * 0.35), (int)($h * 0.75));
        $sx1 = random_int(0, (int)($w * 0.2));
        $sx2 = random_int((int)($w * 0.8), $w);
        $strikeColor = '#6b7280'; // gray-500
        $strikeWidth = random_int(2, 3);
        $strike = "<path d='M {$sx1} {$sy1} L {$sx2} {$sy1}' stroke='{$strikeColor}' stroke-width='{$strikeWidth}' stroke-linecap='round' opacity='0.75' />";

        // filter blur tipis untuk background noise
        $stdBlur = 0.4;

        // rotasi global ringan keseluruhan teks (opsional)
        $globalRotate = random_int(-8, 8);

        // susun SVG (pakai defs untuk filter)
        $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="{$w}" height="{$h}" viewBox="0 0 {$w} {$h}">
  <defs>
    <filter id="bgBlur">
      <feGaussianBlur stdDeviation="{$stdBlur}" />
    </filter>
  </defs>

  <!-- background -->
  <rect width="100%" height="100%" fill="{$bg}" />

  <!-- noise belakang (sedikit blur) -->
  <g filter="url(#bgBlur)">
    {$bgLines}
    {$dots}
    <path d="{$wavyPath}" fill="none" stroke="#e5e7eb" stroke-width="2" opacity="0.9"/>
  </g>

  <!-- group kode captcha + rotasi global ringan -->
  <g transform="rotate({$globalRotate} {$cx} {$cy})">
    {$charSvg}
  </g>

  <!-- garis coret di depan teks -->
  {$strike}
</svg>
SVG;

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
