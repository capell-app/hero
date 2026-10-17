<?php

declare(strict_types=1);

use Capell\Core\Data\VendorAssetData;
use Capell\Core\Facades\CapellCore;
use Capell\Tests\Support\InstalledRuntimeParity;
use Capell\Tests\Support\InstalledRuntimeState;
use Capell\Tests\Support\InstalledRuntimeTestCase;

it('matches fresh installed boot after in-process installation and repeated refresh', function (): void {
    InstalledRuntimeParity::assertPackage('hero');
});

it('keeps Tailwind inputs unavailable until the package is installed', function (): void {
    InstalledRuntimeState::run(static function (): void {
        InstalledRuntimeTestCase::boot(InstalledRuntimeParity::manifest('hero'), false, sourceRoot: dirname(__DIR__, 3));

        expect(CapellCore::getAllVendorAssets()->flatten(1)->filter(
            static fn (VendorAssetData $asset): bool => $asset->packageName === 'capell-app/hero',
        ))->toBeEmpty();
    });
});
