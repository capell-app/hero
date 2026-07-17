<?php

declare(strict_types=1);

namespace Capell\Hero\Enums;

use Filament\Support\Contracts\HasLabel;

enum HeroBackgroundMode: string implements HasLabel
{
    case Inherit = 'inherit';
    case Default = 'default';
    case Custom = 'custom';
    case Off = 'off';

    /**
     * @return array<string, string>
     */
    public static function options(self ...$modes): array
    {
        $options = [];

        foreach ($modes as $mode) {
            $options[$mode->value] = $mode->getLabel();
        }

        return $options;
    }

    public function getLabel(): string
    {
        return __('capell-hero::form.hero_background.mode_options.' . $this->value);
    }
}
