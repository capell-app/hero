<?php

declare(strict_types=1);

use Capell\Core\Contracts\Media\MediaFieldFactory;
use Capell\Hero\Enums\HeroBackgroundMode;
use Capell\Hero\Enums\HeroBackgroundOverlayOpacity;
use Capell\Hero\Enums\HeroBackgroundOverlayStyle;
use Capell\Hero\Enums\HeroMediaMode;
use Capell\Hero\Enums\HeroMediaPreload;
use Capell\Hero\Filament\Components\Forms\HeroBackgroundSchema;
use Capell\Hero\Filament\Extenders\HeroBackgroundThemeSchemaExtender;
use Capell\Hero\Filament\Extenders\HeroBackgroundWidgetAssetSchemaExtender;
use Capell\Hero\Filament\Extenders\HeroBackgroundWidgetSchemaExtender;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;

test('theme background media fields can be built with a non spatie media factory', function (): void {
    app()->bind(MediaFieldFactory::class, static fn (): MediaFieldFactory => new class implements MediaFieldFactory
    {
        #[Override]
        public function make(string $name): TextInput
        {
            return TextInput::make($name . '_id');
        }
    });

    $components = HeroBackgroundSchema::theme();

    expect($components)->toHaveCount(3);
});

it('builds theme widget and asset hero schema groups', function (): void {
    expect(HeroBackgroundSchema::theme())
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Fieldset::class)
        ->and(HeroBackgroundSchema::widget())
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Fieldset::class)
        ->and(HeroBackgroundSchema::asset())
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Fieldset::class);
});

it('appends hero schema components through registered extenders', function (): void {
    $schema = Schema::make();
    $baseComponents = [TextInput::make('existing')];

    expect((new HeroBackgroundThemeSchemaExtender)->extendSettingsComponents($schema, $baseComponents))
        ->toHaveCount(4)
        ->and((new HeroBackgroundWidgetSchemaExtender)->extendDisplayComponents($schema, $baseComponents))
        ->toHaveCount(4)
        ->and((new HeroBackgroundWidgetAssetSchemaExtender)->extendAssetComponents($schema, $baseComponents))
        ->toHaveCount(4)
        ->and((new HeroBackgroundWidgetAssetSchemaExtender)->extendRepeaterComponents($baseComponents))
        ->toHaveCount(4);
});

it('labels hero background and media options from enums', function (): void {
    expect(HeroBackgroundMode::Default->getLabel())->toBe(__('capell-hero::form.hero_background.mode_options.default'))
        ->and(HeroBackgroundMode::Custom->getLabel())->toBe(__('capell-hero::form.hero_background.mode_options.custom'))
        ->and(HeroBackgroundMode::Off->getLabel())->toBe(__('capell-hero::form.hero_background.mode_options.off'))
        ->and(HeroBackgroundOverlayStyle::Mesh->getLabel())->toBe(__('capell-hero::form.hero_background.overlay_options.mesh'))
        ->and(HeroBackgroundOverlayOpacity::ThirtyTwo->getLabel())->toBe(__('capell-hero::form.hero_background.overlay_opacity_options.0_32'))
        ->and(HeroMediaMode::Inherit->getLabel())->toBe(__('capell-hero::form.hero_media.mode_options.inherit'))
        ->and(HeroMediaMode::Custom->getLabel())->toBe(__('capell-hero::form.hero_media.mode_options.custom'))
        ->and(HeroMediaPreload::Metadata->getLabel())->toBe(__('capell-hero::form.hero_media.preload_options.metadata'));
});

it('offers a local custom media action without clearing saved uploads in every form', function (string $context, string $prefix): void {
    $host = new class extends Component implements HasSchemas
    {
        use InteractsWithSchemas;

        /** @var array<string, mixed> */
        public array $data = [];
    };
    $components = HeroBackgroundSchema::{$context}();
    $schema = Schema::make($host)->statePath('data')->components($components);
    $schema->fill();
    if ($prefix === '') {
        $host->data['hero_media'] = ['mode' => 'inherit'];
    } else {
        $host->data['meta'] = ['hero_media' => ['mode' => 'inherit']];
    }

    $uploads = $components[2]->getChildSchema()->getComponents();
    $field = $uploads[0];
    $field->state('saved-video.mp4');

    $actions = $field->getHintActions();
    expect($actions)->toHaveCount(1);
    $action = array_values($actions)[0];
    $action->call();
    expect(data_get($host->data, $prefix . 'hero_media.mode'))->toBe('custom')
        ->and($field->getState())->toBe('saved-video.mp4')
        ->and($field->getHelperTextId())->not->toBeNull()
        ->and($components[1]->getChildSchema()->getComponents()[1]->isVisible())->toBeTrue();
})->with([['theme', ''], ['widget', ''], ['asset', 'meta.']]);
