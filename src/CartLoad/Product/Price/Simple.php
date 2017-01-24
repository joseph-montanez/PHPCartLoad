<?php
namespace CartLoad\Product\Price;

use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\PriceTrait;

class Simple implements PriceInterface
{
    use PriceTrait;

    /**
     * Simple constructor.
     * @param float $price
     */
    public function __construct($price = 0.00)
    {
        $this->setPrice($price);
    }
}