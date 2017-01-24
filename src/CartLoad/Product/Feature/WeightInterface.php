<?php namespace CartLoad\Product\Feature;


interface WeightInterface
{
    const WEIGHT_REPLACE_ALL = 0;
    const WEIGHT_COMBINE = 1;

    /**
     * @return float
     */
    public function getWeight();

    /**
     * @param float $weight
     * @return self
     */
    public function setWeight($weight);

    /**
     * @return int
     */
    public function getWeightEffect();

    /**
     * @param int $weight_effect
     * @return self
     */
    public function setWeightEffect($weight_effect);
}