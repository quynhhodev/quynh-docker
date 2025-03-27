<?php

namespace App\Service\PromotionProduct;

class BasePriceCalculator implements DiscountInterface
{
    public function apply(float $price, int $quantity): float
    {
        return $price * $quantity;
    }
}