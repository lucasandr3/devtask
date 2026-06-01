<?php

namespace Tests\Unit;

use App\Enums\SiteLeadSegment;
use PHPUnit\Framework\TestCase;

class SiteLeadSegmentTest extends TestCase
{
    public function test_translates_known_segments(): void
    {
        $this->assertSame('Saúde', SiteLeadSegment::labelFor('healthcare'));
        $this->assertSame('Outro', SiteLeadSegment::labelFor('other'));
        $this->assertSame('Imobiliário', SiteLeadSegment::labelFor('real_estate'));
    }

    public function test_normalizes_hyphens(): void
    {
        $this->assertSame('Imobiliário', SiteLeadSegment::labelFor('real-estate'));
    }

    public function test_returns_dash_for_empty(): void
    {
        $this->assertSame('—', SiteLeadSegment::labelFor(null));
        $this->assertSame('—', SiteLeadSegment::labelFor(''));
    }
}
