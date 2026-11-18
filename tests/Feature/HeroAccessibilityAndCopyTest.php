<?php

declare(strict_types=1);

use Capell\Core\Models\Language;
use Capell\Core\Models\Page;
use Capell\Core\Models\Site;
use Capell\Core\Models\Theme;
use Capell\Frontend\Support\State\FrontendState;
use Capell\Hero\View\Components\Widget\Hero;
use Capell\LayoutBuilder\Enums\WidgetComponentEnum;
use Capell\LayoutBuilder\Models\Widget;
use Capell\LayoutBuilder\Models\WidgetAsset;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * @param  array<string, mixed>  $widgetMeta
 * @param  array<string, mixed>  $pageMeta
 * @param  int  $slideCount  number of linked slide pages to attach to the widget
 */
function renderHeroForCopyAndAccessibility(array $widgetMeta = [], array $pageMeta = [], int $slideCount = 0): string
{
    $language = Language::factory()->english()->create();
    $theme = Theme::factory()->defaultMeta()->create();
    $site = Site::factory()
        ->language($language)
        ->theme($theme)
        ->withTranslations($language, ['title' => 'Capell'])
        ->create();

    $page = Page::factory()
        ->site($site)
        ->withTranslations($language, [
            'title' => 'Hero Host',
            'content' => '<p>Body content.</p>',
            'meta' => $pageMeta + ['slug' => 'hero-host'],
        ])
        ->create();

    $page->load('translation');
    $site->load('translation');

    $assets = [];

    for ($index = 1; $index <= $slideCount; $index++) {
        $slidePage = Page::factory()
            ->site($site)
            ->withTranslations($language, [
                'title' => 'Slide ' . $index,
                'content' => '<p>Slide copy.</p>',
                'meta' => ['slug' => 'slide-' . $index],
            ])
            ->create();
        $slidePage->load('translation');
        $slidePage->setRelation('pageUrl', null);

        $asset = new WidgetAsset;
        $asset->setRelation('asset', $slidePage);
        $asset->setRelation('media', new EloquentCollection);
        $assets[] = $asset;
    }

    $widget = Widget::factory()->create([
        'key' => 'hero',
        'meta' => ['component' => WidgetComponentEnum::Hero->value, 'color' => 'light'] + $widgetMeta,
    ]);
    $widget->setRelation('assets', new EloquentCollection($assets));

    resolve(FrontendState::class)
        ->withLanguage($language)
        ->withSite($site)
        ->withTheme($theme)
        ->withPage($page);

    $rendered = (new Hero(
        container: [],
        containerKey: 'main',
        widgetIndex: 0,
        loop: (object) ['first' => true, 'last' => true],
        widget: $widget,
    ))->render();

    if ($rendered instanceof View) {
        return $rendered->render();
    }

    return $rendered instanceof Closure ? '' : $rendered;
}

it('renders the full height option as a full-viewport hero', function (): void {
    $html = renderHeroForCopyAndAccessibility(['height' => 'full'], ['hero_title' => 'Tall hero']);

    expect($html)
        ->toContain('min-h-[calc(100vh-var(--header-height))]')
        ->not->toContain('min-height: full');
});

it('does not force a viewport height when the editor picks no height', function (): void {
    $html = renderHeroForCopyAndAccessibility([], ['hero_title' => 'Default hero']);

    expect($html)->not->toContain('min-h-[calc(100vh-var(--header-height))]');
});

it('keeps editor copy that matches a translation key and still fills the placeholders', function (): void {
    $html = renderHeroForCopyAndAccessibility(
        pageMeta: [
            'hero_title' => 'auth.failed',
            'hero' => '<p>Welcome to :title</p>',
        ],
    );

    expect($html)
        ->toContain('auth.failed')
        ->not->toContain('These credentials do not match our records')
        ->toContain('Welcome to Hero Host');
});

it('labels the carousel arrows with the namespaced translations', function (): void {
    $html = renderHeroForCopyAndAccessibility(['carousel_arrows' => true], slideCount: 2);

    expect($html)
        ->toContain('swiper-button-prev')
        ->not->toContain('aria-label="generic.previous"')
        ->not->toContain('aria-label="generic.next"')
        ->toContain('aria-label="' . __('capell-frontend::generic.previous') . '"')
        ->toContain('aria-label="' . __('capell-frontend::generic.next') . '"');
});
