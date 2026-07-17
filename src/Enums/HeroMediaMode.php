<?php

declare(strict_types=1);

namespace Capell\Hero\Enums;

use Filament\Support\Contracts\HasLabel;

enum HeroMediaMode: string implements HasLabel
{
    case Inherit = 'inherit';
    case Custom = 'custom';
    case Off = 'off';

    public function getLabel(): string
    {
        return __('capell-hero::form.hero_media.mode_options.' . $this->value);
    }
}
