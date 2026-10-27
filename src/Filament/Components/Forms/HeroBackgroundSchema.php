<?php

declare(strict_types=1);

namespace Capell\Hero\Filament\Components\Forms;

use Capell\Admin\Filament\Components\Forms\MediaLibraryFileUpload;
use Capell\Hero\Data\HeroBackgroundData;
use Capell\Hero\Data\HeroMediaData;
use Capell\Hero\Enums\HeroBackgroundMode;
use Capell\Hero\Enums\HeroBackgroundOverlayOpacity;
use Capell\Hero\Enums\HeroBackgroundOverlayStyle;
use Capell\Hero\Enums\HeroMediaMode;
use Capell\Hero\Enums\HeroMediaPreload;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Contracts\Support\Htmlable;

final class HeroBackgroundSchema
{
    /**
     * @return array<int, Component | Action | ActionGroup | string | Htmlable>
     */
    public static function theme(): array
    {
        return [
            self::fieldset([
                self::modeSelect(HeroBackgroundMode::options(
                    HeroBackgroundMode::Default,
                    HeroBackgroundMode::Custom,
                    HeroBackgroundMode::Off,
                ), HeroBackgroundMode::Default->value)
                    ->helperText(__('capell-hero::form.hero_background.theme_helper')),
            ]),
            self::mediaSettingsFieldset(HeroMediaMode::Off->value),
            self::mediaUploadsFieldset(),
        ];
    }

    /**
     * @return array<int, Component | Action | ActionGroup | string | Htmlable>
     */
    public static function widget(): array
    {
        return [
            self::fieldset([
                self::modeSelect(HeroBackgroundMode::options(
                    HeroBackgroundMode::Inherit,
                    HeroBackgroundMode::Custom,
                    HeroBackgroundMode::Off,
                ), HeroBackgroundMode::Inherit->value)
                    ->helperText(__('capell-hero::form.hero_background.widget_helper')),
            ]),
            self::mediaSettingsFieldset(HeroMediaMode::Inherit->value),
            self::mediaUploadsFieldset(),
        ];
    }

    /**
     * @return array<int, Component | Action | ActionGroup | string | Htmlable>
     */
    public static function asset(): array
    {
        return [
            self::fieldset([
                self::modeSelect(HeroBackgroundMode::options(
                    HeroBackgroundMode::Inherit,
                    HeroBackgroundMode::Custom,
                    HeroBackgroundMode::Off,
                ), HeroBackgroundMode::Inherit->value)
                    ->helperText(__('capell-hero::form.hero_background.asset_helper')),
            ], withMetaStatePath: true),
            self::mediaSettingsFieldset(HeroMediaMode::Inherit->value, withMetaStatePath: true),
            self::mediaUploadsFieldset('meta.'),
        ];
    }

    /**
     * @param  array<int, Component>  $extraModeComponents
     */
    private static function fieldset(array $extraModeComponents, bool $withMetaStatePath = false): Fieldset
    {
        $modeSelect = $extraModeComponents[0];

        $fieldset = Fieldset::make(__('capell-hero::form.hero_background.label'))
            ->columns(['default' => 1, 'md' => 2, 'xl' => 3])
            ->schema([
                $modeSelect,
                ColorPicker::make('hero_background.background_color')
                    ->label(__('capell-hero::form.hero_background.background_color'))
                    ->default('#f4f7fb')
                    ->visible(fn (Get $get): bool => $get('hero_background.mode') === HeroBackgroundData::ModeCustom),
                ColorPicker::make('hero_background.accent_color')
                    ->label(__('capell-hero::form.hero_background.accent_color'))
                    ->default('#315f8f')
                    ->visible(fn (Get $get): bool => $get('hero_background.mode') === HeroBackgroundData::ModeCustom),
                ColorPicker::make('hero_background.accent_color_alt')
                    ->label(__('capell-hero::form.hero_background.accent_color_alt'))
                    ->default('#7aa0c4')
                    ->visible(fn (Get $get): bool => $get('hero_background.mode') === HeroBackgroundData::ModeCustom),
                Select::make('hero_background.overlay_style')
                    ->label(__('capell-hero::form.hero_background.overlay_style'))
                    ->default(HeroBackgroundOverlayStyle::Mesh->value)
                    ->options(HeroBackgroundOverlayStyle::class)
                    ->visible(fn (Get $get): bool => $get('hero_background.mode') === HeroBackgroundData::ModeCustom),
                Select::make('hero_background.overlay_opacity')
                    ->label(__('capell-hero::form.hero_background.overlay_opacity'))
                    ->default(HeroBackgroundOverlayOpacity::ThirtyTwo->value)
                    ->options(HeroBackgroundOverlayOpacity::class)
                    ->visible(fn (Get $get): bool => $get('hero_background.mode') === HeroBackgroundData::ModeCustom),
            ]);

        return $withMetaStatePath ? $fieldset->statePath('meta') : $fieldset;
    }

    /**
     * @param  array<int|string, string>  $options
     */
    private static function modeSelect(array $options, string $default): Select
    {
        return Select::make('hero_background.mode')
            ->label(__('capell-hero::form.hero_background.mode'))
            ->default($default)
            ->options($options)
            ->live();
    }

    private static function mediaSettingsFieldset(string $defaultMode, bool $withMetaStatePath = false): Fieldset
    {
        $fieldset = Fieldset::make(__('capell-hero::form.hero_media.label'))
            ->columns(['default' => 1, 'md' => 2, 'xl' => 3])
            ->schema([
                Select::make('hero_media.mode')
                    ->label(__('capell-hero::form.hero_media.mode'))
                    ->default($defaultMode)
                    ->options(HeroMediaMode::class)
                    ->helperText($withMetaStatePath ? __('capell-hero::form.hero_media.asset_inheritance') : ($defaultMode === HeroMediaMode::Off->value ? __('capell-hero::form.hero_media.theme_inheritance') : __('capell-hero::form.hero_media.widget_inheritance')))
                    ->live(),
                Checkbox::make('hero_media.autoplay')
                    ->label(__('capell-hero::form.hero_media.autoplay'))
                    ->default(true)
                    ->visible(fn (Get $get): bool => self::mediaMode($get, 'hero_media.mode') === HeroMediaData::ModeCustom),
                Checkbox::make('hero_media.loop')
                    ->label(__('capell-hero::form.hero_media.loop'))
                    ->default(true)
                    ->visible(fn (Get $get): bool => self::mediaMode($get, 'hero_media.mode') === HeroMediaData::ModeCustom),
                Checkbox::make('hero_media.muted')
                    ->label(__('capell-hero::form.hero_media.muted'))
                    ->default(true)
                    ->visible(fn (Get $get): bool => self::mediaMode($get, 'hero_media.mode') === HeroMediaData::ModeCustom),
                Checkbox::make('hero_media.pause_when_out_of_view')
                    ->label(__('capell-hero::form.hero_media.pause_when_out_of_view'))
                    ->default(true)
                    ->visible(fn (Get $get): bool => self::mediaMode($get, 'hero_media.mode') === HeroMediaData::ModeCustom),
                Select::make('hero_media.preload')
                    ->label(__('capell-hero::form.hero_media.preload'))
                    ->default(HeroMediaPreload::Metadata->value)
                    ->options(HeroMediaPreload::class)
                    ->visible(fn (Get $get): bool => self::mediaMode($get, 'hero_media.mode') === HeroMediaData::ModeCustom),
            ]);

        return $withMetaStatePath ? $fieldset->statePath('meta') : $fieldset;
    }

    private static function mediaUploadsFieldset(string $prefix = ''): Fieldset
    {
        return Fieldset::make(__('capell-hero::form.hero_media.uploads_label'))
            ->columns(['default' => 1, 'lg' => 3])
            ->schema(array_map(static fn (Field $field): Field => $field
                ->helperText(fn (Get $get): string => __('capell-hero::form.hero_media.upload_help.' . (self::mediaMode($get, $prefix . 'hero_media.mode') === HeroMediaData::ModeCustom && $prefix === 'meta.' ? 'custom_asset' : self::mediaMode($get, $prefix . 'hero_media.mode'))))
                ->hintAction(Action::make('useCustomMedia')
                    ->label(__('capell-hero::form.hero_media.use_custom'))
                    ->visible(fn (Get $get): bool => self::mediaMode($get, $prefix . 'hero_media.mode') !== HeroMediaData::ModeCustom)
                    ->action(fn (Set $set): mixed => $set($prefix . 'hero_media.mode', HeroMediaData::ModeCustom))), [
                        self::videoUpload(HeroMediaData::CollectionDesktopVideo, __('capell-hero::form.hero_media.desktop_video')),
                        self::videoUpload(HeroMediaData::CollectionTabletVideo, __('capell-hero::form.hero_media.tablet_video')),
                        self::videoUpload(HeroMediaData::CollectionMobileVideo, __('capell-hero::form.hero_media.mobile_video')),
                        self::imageUpload(HeroMediaData::CollectionDesktopImage, __('capell-hero::form.hero_media.desktop_image')),
                        self::imageUpload(HeroMediaData::CollectionTabletImage, __('capell-hero::form.hero_media.tablet_image')),
                        self::imageUpload(HeroMediaData::CollectionMobileImage, __('capell-hero::form.hero_media.mobile_image')),
                    ]));
    }

    private static function mediaMode(Get $get, string $path): string
    {
        $mode = $get($path);

        return $mode instanceof HeroMediaMode ? $mode->value : (is_string($mode) ? $mode : HeroMediaData::ModeOff);
    }

    private static function videoUpload(string $collection, string $label): Field
    {
        $component = self::mediaUpload($collection)
            ->label($label);

        if ($component instanceof SpatieMediaLibraryFileUpload) {
            $component
                ->acceptedFileTypes(['video/mp4', 'video/webm'])
                ->collection($collection);
        }

        return $component;
    }

    private static function imageUpload(string $collection, string $label): Field
    {
        $component = self::mediaUpload($collection)
            ->label($label);

        if ($component instanceof SpatieMediaLibraryFileUpload) {
            $component
                ->image()
                ->collection($collection);
        }

        return $component;
    }

    private static function mediaUpload(string $collection): Field
    {
        return MediaLibraryFileUpload::make($collection);
    }
}
