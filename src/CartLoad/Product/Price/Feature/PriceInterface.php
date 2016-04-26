<?php namespace CartLoad\Product\Price\Feature;


interface PriceInterface {
    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param float $price
     * @return self
     */
    public function setPrice(float $price);
}