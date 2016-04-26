<?php
namespace CartLoad\Product\Price;

use CartLoad\Product\Price\Feature\PriceInterface;
use CartLoad\Product\Price\Feature\PriceTrait;

class Simple implements PriceInterface {
	use PriceTrait;

    public function __construct(float $price = 0.00) {
        $this->setPrice($price);
    }
}