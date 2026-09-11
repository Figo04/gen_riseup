<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class MateriComponentsTest extends TestCase
{
    public function test_highlight_defaults_to_mint_variant(): void
    {
        $html = Blade::render('<x-highlight title="Tujuan">isi</x-highlight>');

        $this->assertStringContainsString('bg-brand-mint-light', $html);
        $this->assertStringContainsString('Tujuan', $html);
    }

    public function test_highlight_peach_variant_switches_classes(): void
    {
        $html = Blade::render('<x-highlight variant="peach">isi</x-highlight>');

        $this->assertStringContainsString('bg-brand-peach-light', $html);
        $this->assertStringNotContainsString('bg-brand-mint-light', $html);
    }

    public function test_checklist_item_warning_variant_uses_warning_icon(): void
    {
        $html = Blade::render('<x-checklist-item variant="warning">isi</x-checklist-item>');

        $this->assertStringContainsString('⚠️', $html);
        $this->assertStringNotContainsString('✅', $html);
    }
}
