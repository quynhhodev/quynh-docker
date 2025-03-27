<?php

namespace App\Service\PromotionProduct;

class QuantityDiscountDecorator extends PriceCalculatorDecorator
{
    private int $threshold;
    private float $discountRate;

    public function __construct(DiscountInterface $discount, int $threshold = 10, float $discountRate = 0.1)
    {
        parent::__construct($discount);
        $this->threshold = $threshold;
        $this->discountRate = $discountRate;
    }

    public function apply(float $price, int $quantity): float
    {
        $total = $this->discount->apply($price, $quantity);

        if ($quantity > $this->threshold) {
            return $total - ($total * $this->discountRate);
        }

        return $total;
    }
}