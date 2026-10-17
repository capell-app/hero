<?php

declare(strict_types=1);

use Capell\Core\Enums\MediaCollectionEnum;
use Capell\Core\Models\Media;
use Capell\Core\Models\Page;
use Capell\Hero\Data\HeroAssetSlideData;
use Capell\Hero\Health\HeroHealthCheck;
use Capell\LayoutBuilder\Models\Widget;
use Capell\LayoutBuilder\Models\WidgetAsset;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\View\ComponentAttributeBag;

it('builds hero asset slide data from a linked page asset', function (): void {
    $page = Page::factory()->create(['meta' => ['color' => 'brand']]);
    $widget = new Widget;
    $widgetAsset = new WidgetAsset;
    $backgroundImage = new Media;
    $backgroundImage->collection_name = MediaCollectionEnum::BackgroundImage->value;

    $image = new Media;
    $image->collection_name = MediaCollectionEnum::Image->value;

    $page->setRelation('pageUrl', null);
    $widgetAsset->setRelation('asset', $page);
    $widgetAsset->setRelation('media', new EloquentCollection([$backgroundImage, $image]));

    $data = HeroAssetSlideData::fromWidgetAsset($widgetAsset, $widget, 'fallback');

    expect($data->asset)->toBe($page)
        ->and($data->color)->toBe('brand')
        ->and($data->linkedPage)->toBe($page)
        ->and($data->backgroundImage)->toBe($backgroundImage)
        ->and($data->images?->all())->toBe([$image])
        ->and(HeroHealthCheck::compatibleCapellApiVersion())->toBe('^1.0');
});

it('uses media assets directly as the hero background image', function (): void {
    $widget = new Widget;
    $widgetAsset = new WidgetAsset;
    $media = new Media;
    $media->collection_name = MediaCollectionEnum::Image->value;

    $widgetAsset->setRelation('asset', $media);

    $data = HeroAssetSlideData::fromWidgetAsset($widgetAsset, $widget, 'fallback');

    expect($data->asset)->toBe($media)
        ->and($data->color)->toBe('fallback')
        ->and($data->linkedPage)->toBeNull()
        ->and($data->backgroundImage)->toBe($media)
        ->and($data->images)->toBeNull();
});

it('does not lazy load optional public hero relations', function (): void {
    $previousLazyLoadingState = Model::preventsLazyLoading();
    Model::preventLazyLoading();

    try {
        $page = Page::factory()->create(['meta' => ['color' => 'brand']]);
        $widget = new Widget;
        $widgetAsset = new WidgetAsset;
        $widgetAsset->setRelation('asset', $page);

        $data = HeroAssetSlideData::fromWidgetAsset($widgetAsset, $widget, 'fallback');
    } finally {
        Model::preventLazyLoading($previousLazyLoadingState);
    }

    expect($data->linkedPage)->toBe($page)
        ->and($data->url)->toBeNull()
        ->and($data->backgroundImage)->toBeNull()
        ->and($data->images)->toBeNull();
});

it('renders prepared related hero content without queries or public editor context', function (): void {
    $related = collect([[
        'title' => '<script>unsafe title</script>', 'summary' => 'Prepared related summary',
        'url' => '/related', 'linkText' => 'Read more',
    ]]);
    DB::flushQueryLog();
    DB::enableQueryLog();

    try {
        $html = view('capell-hero::components.hero.related', [
            'related' => $related, 'key' => 'related',
            'attributes' => new ComponentAttributeBag,
        ])->render();
        $queries = DB::getQueryLog();
    } finally {
        DB::disableQueryLog();
    }

    expect($html)->toContain('Prepared related summary', 'href="/related"', 'Read more', '&lt;script&gt;')
        ->not->toContain('<script>', 'widget_key', 'site_id', 'page_id')
        ->and($queries)->toBe([]);
});
