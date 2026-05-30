<?php

namespace Tests\Feature;

use App\Support\PaymentDisplay;
use Tests\TestCase;

class PaymentDisplayFormattingTest extends TestCase
{
    public function test_euro_formatting_uses_human_readable_notation(): void
    {
        $this->assertSame('€ 250,00', PaymentDisplay::euroFromCents(25000));
        $this->assertSame('€ 1.495,00', PaymentDisplay::euroFromCents(149500));
    }
}
