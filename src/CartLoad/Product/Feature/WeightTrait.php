<?php namespace CartLoad\Product\Feature;


trait WeightTrait
{
    /** @var float $weight */
    protected $weight;

    /** @var int $weight_effect */
    protected $weight_effect;

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return WeightTrait
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeightEffect()
    {
        return $this->weight_effect;
    }

    /**
     * @param int $weight_effect
     * @return WeightTrait
     */
    public function setWeightEffect($weight_effect)
    {
        $this->weight_effect = $weight_effect;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return WeightTrait
     */
    public function weightFromArray(array $value)
    {
        if (isset($value['weight'])) {
            $this->setWeight($value['weight']);
        }
        if (isset($value['weight_effect'])) {
            $this->setWeightEffect($value['weight_effect']);
        } else {
            $this->setWeightEffect(PriceInterface::PRICE_COMBINE);
        }

        return $this;
    }
}