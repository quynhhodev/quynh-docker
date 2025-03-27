<?php

namespace App\Service\PromotionProduct;

interface DiscountInterface
{
    public function apply(float $price, int $quantity): float;
}
