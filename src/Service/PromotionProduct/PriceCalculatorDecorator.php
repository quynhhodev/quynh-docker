<?php

namespace App\Service\PromotionProduct;

abstract class PriceCalculatorDecorator implements DiscountInterface
{
    protected DiscountInterface $discount;

    public function __construct(DiscountInterface $discount)
    {
        $this->discount = $discount;
    }
}