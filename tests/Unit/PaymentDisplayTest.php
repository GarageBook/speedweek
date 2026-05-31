<?php

namespace Tests\Unit;

use App\Models\Registration;
use App\Support\PaymentDisplay;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaymentDisplayTest extends TestCase
{
    #[Test]
    public function it_formats_euro_from_cents_in_dutch_notation(): void
    {
        $this->assertSame('€ 1.495,00', PaymentDisplay::euroFromCents(149500));
        $this->assertSame('€ 250,00', PaymentDisplay::euroFromCents(25000));
    }

    #[Test]
    public function it_builds_clear_payment_summary_based_on_invoice_statuses(): void
    {
        $registration = new Registration();
        $registration->setRelation('invoices', new Collection([
            (object) ['type' => 'deposit', 'status' => 'paid'],
            (object) ['type' => 'final', 'status' => 'sent'],
        ]));

        $this->assertSame(
            'Aanbetaling: voldaan • Restfactuur: niet voldaan',
            PaymentDisplay::registrationPaymentSummary($registration)
        );
    }
}
