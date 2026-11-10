# Hero reference

<!-- prettier-ignore-start -->

Developer reference for `capell-app/hero`. Start with the [README](../README.md) for what the extension does and how to install it.

## Package Details

Hero is an **Available**, **No schema impact** Capell package in the **Capell Essentials** product group. It ships as `capell-app/hero` and extends these surfaces: admin, frontend, console.

- Status: Available
- Tier: free
- Bundle: foundation
- Composer package: `capell-app/hero`
- Namespace: `Capell\Hero`
- Theme key: not applicable

## Technical Shape

### Service providers

- `Capell\Hero\Providers\HeroServiceProvider`

### Filament classes

- `HeroBackgroundSchema`
- `HeroBackgroundThemeSchemaExtender`
- `HeroBackgroundWidgetAssetSchemaExtender`
- `HeroBackgroundWidgetSchemaExtender`

### Actions

- `InstallHeroLayoutDefaultsAction`
- `ResolveHeroBackgroundDataAction`
- `ResolveHeroMediaDataAction`

### Data objects

- `HeroAssetSlideData`
- `HeroBackgroundData`
- `HeroMediaData`
- `HeroWidgetRenderData`

### Command signatures

- `capell:hero-setup`

### Console command classes

- `SetupCommand`

### Health checks

- `Capell\Hero\Health\HeroHealthCheck`

### Blade views

- `packages/hero/resources/views/components/hero/background.blade.php`
- `packages/hero/resources/views/components/hero/content.blade.php`
- `packages/hero/resources/views/components/hero/media.blade.php`
- `packages/hero/resources/views/components/hero/related.blade.php`
- `packages/hero/resources/views/components/hero/slide.blade.php`
- `packages/hero/resources/views/components/hero/wrapper.blade.php`
- `packages/hero/resources/views/components/widget/default.blade.php`
- `packages/hero/resources/views/components/widget/hero.blade.php`

### Cache tags

- `hero`


## Data Model

This package has no schema impact. It registers runtime behaviour through `Capell\Hero\Providers\HeroServiceProvider` while persistence remains with Capell core or required packages.

<!-- prettier-ignore-end -->
