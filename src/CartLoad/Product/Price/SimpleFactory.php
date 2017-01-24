<?php namespace CartLoad\Product\Price;


class SimpleFactory
{
    /**
     * @param float $price
     * @return Simple
     */
    public function make($price)
    {
        return new Simple($price);
    }

}