<?php

declare(strict_types=1);

use Capell\Core\Facades\CapellCore;
use Capell\FoundationTheme\Support\Tailwind\TailwindAssetsGenerator;
use Capell\Hero\Providers\HeroServiceProvider;
use Capell\Tests\Support\OwnedTestbenchSkeleton;
use Illuminate\Filesystem\Filesystem;

/*
 * Task 0.2 regression guard: the demo serves exactly one compiled stylesheet
 * (the theme-foundation Tailwind entrypoint), so hero.css must be pulled into
 * that entrypoint via the vendor-asset TailwindImport mechanism whenever the
 * hero package is installed — otherwise the hero background overlay loses all
 * of its styling on the public site.
 */

it('imports hero.css into the compiled frontend entrypoint when the hero package is installed', function (): void {
    OwnedTestbenchSkeleton::useStorageProject(app());
    $targetPath = storage_path('framework/testing/capell-hero-tailwind/frontend.css');

    CapellCore::forcePackageInstalled(HeroServiceProvider::$packageName);

    new TailwindAssetsGenerator(new Filesystem)->generate($targetPath);

    $entrypoint = (new Filesystem)->get($targetPath);

    expect($entrypoint)->toContain('capell-app/hero/resources/css/hero.css');
});

it('ships the hero-background overlay rules in the imported stylesheet', function (): void {
    $stylesheet = (new Filesystem)->get(dirname(__DIR__, 2) . '/resources/css/hero.css');

    expect($stylesheet)
        ->toContain('.hero-background__ribbons')
        ->toContain('.hero-background__grid path')
        ->toContain('.hero-background__contours path');
});
