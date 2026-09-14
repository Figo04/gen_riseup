<?php

namespace Tests\Feature;

use App\Providers\AppServiceProvider;
use RuntimeException;
use Tests\TestCase;

class DebugProductionGuardTest extends TestCase
{
    public function test_boot_menolak_debug_menyala_di_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config(['app.debug' => true]);

        $this->expectException(RuntimeException::class);

        (new AppServiceProvider($this->app))->boot();
    }

    public function test_boot_meloloskan_production_tanpa_debug(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config(['app.debug' => false]);

        $this->expectNotToPerformAssertions();

        (new AppServiceProvider($this->app))->boot();
    }

    public function test_boot_meloloskan_debug_menyala_di_luar_production(): void
    {
        $this->app->detectEnvironment(fn () => 'local');
        config(['app.debug' => true]);

        $this->expectNotToPerformAssertions();

        (new AppServiceProvider($this->app))->boot();
    }
}
