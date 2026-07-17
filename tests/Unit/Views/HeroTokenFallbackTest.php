<?php

declare(strict_types=1);

use Capell\Hero\Data\HeroBackgroundData;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;

/*
 * Task 0.2: every --hero-* custom-property read must carry a fallback so an
 * unset token (theme without hero styling, stripped inline style attribute)
 * never produces an invalid color — the background then degrades to the
 * HeroBackgroundData::defaults() palette instead of rendering black.
 */

it('gives every hero token read in hero.css a fallback value', function (): void {
    $stylesheet = (new Filesystem)->get(dirname(__DIR__, 3) . '/resources/css/hero.css');

    preg_match_all('/var\(--hero-[a-z-]+(?<fallback>,[^)]+)?\)/', $stylesheet, $matches, PREG_SET_ORDER);

    expect($matches)->not->toBeEmpty();

    foreach ($matches as $match) {
        expect(trim((string) ($match['fallback'] ?? ''), ', '))
            ->not->toBe('', 'Missing fallback in: ' . $match[0]);
    }
});

it('gives every hero token read in the background SVG a fallback value', function (): void {
    $background = HeroBackgroundData::defaults();

    $html = Blade::render(
        '<x-capell-hero::hero.background :background="$background" instance-key="fallback-test" />',
        ['background' => $background],
    );

    preg_match_all('/var\(--hero-[a-z-]+(?<fallback>,[^)]+)?\)/', $html, $matches, PREG_SET_ORDER);

    expect($matches)->not->toBeEmpty();

    foreach ($matches as $match) {
        expect(trim((string) ($match['fallback'] ?? ''), ', '))->not->toBe('');
    }

    expect($html)
        ->toContain('var(--hero-accent-color, #315f8f)')
        ->toContain('var(--hero-accent-color-alt, #7aa0c4)')
        ->toContain('var(--hero-background-color, #f4f7fb)');
});
