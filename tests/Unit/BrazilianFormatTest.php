<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BrazilianFormatTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        require_once dirname(__DIR__, 2).'/app/Helpers/BrazilianFormat.php';
    }

    public function test_parse_brazilian_money_with_currency_mask(): void
    {
        $this->assertSame(1234.56, parse_brazilian_money('R$ 1.234,56'));
    }

    public function test_parse_brazilian_money_without_prefix(): void
    {
        $this->assertSame(850.0, parse_brazilian_money('850,00'));
        $this->assertSame(1500.5, parse_brazilian_money('1.500,50'));
    }

    public function test_parse_brazilian_money_dot_decimal_from_database(): void
    {
        $this->assertSame(99.9, parse_brazilian_money('99.90'));
    }

    public function test_parse_brazilian_decimal_hours(): void
    {
        $this->assertSame(220.0, parse_brazilian_decimal('220,00'));
        $this->assertSame(176.5, parse_brazilian_decimal('176,50'));
    }

    public function test_format_brazilian_money(): void
    {
        $this->assertSame('R$ 1.234,56', format_brazilian_money(1234.56));
    }
}
