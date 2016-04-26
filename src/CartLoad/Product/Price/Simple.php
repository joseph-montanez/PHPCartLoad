<?php
namespace CartLoad\Product\Price;

use CartLoad\Product\Price\Feature\PriceInterface;
use CartLoad\Product\Price\Feature\PriceTrait;

/**
 * Created by PhpStorm.
 * User: josephmontanez
 * Date: 4/25/16
 * Time: 4:13 PM
 */
class Simple implements PriceInterface {
	use PriceTrait;

    public function __construct(float $price = 0.00) {
        $this->setPrice($price);
    }
}