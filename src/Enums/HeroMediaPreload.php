<?php

declare(strict_types=1);

namespace Capell\Hero\Enums;

use Filament\Support\Contracts\HasLabel;

enum HeroMediaPreload: string implements HasLabel
{
    case None = 'none';
    case Metadata = 'metadata';
    case Auto = 'auto';

    public function getLabel(): string
    {
        return __('capell-hero::form.hero_media.preload_options.' . $this->value);
    }
}
