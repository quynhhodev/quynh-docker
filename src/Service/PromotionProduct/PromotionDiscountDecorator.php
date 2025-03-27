<?php

namespace App\Service\PromotionProduct;

class PromotionDiscountDecorator extends PriceCalculatorDecorator
{
    private float $discountRate;

    public function __construct(DiscountInterface $discount, float $discountRate)
    {
        parent::__construct($discount);
        $this->discountRate = $discountRate;
    }

    public function apply(float $price, int $quantity): float
    {
        $total = $this->discount->apply($price, $quantity);
        return $total * (1 - $this->discountRate);
    }
}