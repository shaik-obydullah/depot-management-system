<?php

use App\Libraries\SaleCalculator;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class SaleCalculatorTest extends CIUnitTestCase
{
    public function testTotalsUseQuantityTimesPrice(): void
    {
        $totals = SaleCalculator::totals([
            ['quantity' => 2, 'price' => 10.5],
            ['quantity' => 3, 'price' => 4.0],
        ]);

        $this->assertSame(33.0, $totals['subtotal']);
        $this->assertSame(0.0, $totals['discount']);
        $this->assertSame(33.0, $totals['total']);
    }

    public function testDiscountIsSubtractedAndNeverNegative(): void
    {
        $totals = SaleCalculator::totals([
            ['quantity' => 1, 'price' => 20.0],
        ], 5.0);

        $this->assertSame(5.0, $totals['discount']);
        $this->assertSame(15.0, $totals['total']);

        $discounted = SaleCalculator::totals([
            ['quantity' => 1, 'price' => 10.0],
        ], 50.0);

        $this->assertSame(0.0, $discounted['total']);
    }
}
