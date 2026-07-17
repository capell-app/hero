<?php

declare(strict_types=1);

namespace Capell\Hero\Enums;

use Filament\Support\Contracts\HasLabel;

enum HeroBackgroundOverlayStyle: string implements HasLabel
{
    case Mesh = 'mesh';
    case Ribbons = 'ribbons';
    case Grid = 'grid';
    case Contours = 'contours';

    public function getLabel(): string
    {
        return __('capell-hero::form.hero_background.overlay_options.' . $this->value);
    }
}
