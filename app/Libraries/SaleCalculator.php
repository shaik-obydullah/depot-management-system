<?php

namespace App\Libraries;

/**
 * Pure sale totals helper, kept separate from the model so it can be tested
 * without a database.
 */
final class SaleCalculator
{
    /**
     * @param list<array{quantity: int, price: float}> $items
     *
     * @return array{subtotal: float, discount: float, total: float}
     */
    public static function totals(array $items, float $discount = 0.0): array
    {
        $subtotal = 0.0;

        foreach ($items as $item) {
            $subtotal += ((int) $item['quantity']) * ((float) $item['price']);
        }

        $discount = max(0.0, $discount);

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'total'    => round(max(0.0, $subtotal - $discount), 2),
        ];
    }
}
