<?php

declare(strict_types=1);

use Capell\Hero\Data\HeroBackgroundData;
use Illuminate\Support\Facades\Blade;

it('renders decorative hero paths with safe presentation defaults', function (): void {
    $background = HeroBackgroundData::defaults();

    $html = Blade::render(
        '<x-capell-hero::hero.background :background="$background" instance-key="test" />',
        ['background' => $background],
    );

    expect(substr_count($html, 'class="hero-background__ribbons"'))->toBe(2)
        ->and($html)->not->toMatch('/class="hero-background__(ribbons|grid|contours)"(?![^>]*fill="none")/');
});
