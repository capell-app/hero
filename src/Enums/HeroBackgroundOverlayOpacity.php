<?php

declare(strict_types=1);

namespace Capell\Hero\Enums;

use Filament\Support\Contracts\HasLabel;

enum HeroBackgroundOverlayOpacity: string implements HasLabel
{
    case Sixteen = '0.16';
    case TwentyFour = '0.24';
    case ThirtyTwo = '0.32';
    case FortyFour = '0.44';
    case FiftySix = '0.56';

    public function getLabel(): string
    {
        return __('capell-hero::form.hero_background.overlay_opacity_options.' . str_replace('.', '_', $this->value));
    }
}
