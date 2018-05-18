<?php namespace CartLoad\Product\Feature;


interface PriceInterface
{
    const PRICE_REPLACE_ALL = 0;
    const PRICE_COMBINE = 1;

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param float $price
     *
     * @return self
     */
    public function setPrice($price);

    /**
     * @return int
     */
    public function getPriceEffect();

    /**
     * @param int $price_effect
     *
     * @return PriceTrait
     */
    public function setPriceEffect($price_effect);

    /**
     * @param array $value
     *
     * @return PriceTrait
     */
    public function priceFromArray(array $value);
}