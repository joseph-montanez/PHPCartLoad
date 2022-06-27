<?php namespace CartLoad\Product\Feature;

trait PriceTrait
{
    /** @var float|null $price */
    protected ?float $price;

    /** @var int $price_effect */
    protected $price_effect;

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price ?? 0.00;
    }

    /**
     * @param float $price
     *
     * @return self
     */
    public function setPrice($price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceEffect()
    {
        return $this->price_effect;
    }

    /**
     * @param int $price_effect
     *
     * @return PriceTrait
     */
    public function setPriceEffect($price_effect)
    {
        $this->price_effect = $price_effect;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return PriceTrait
     */
    public function priceFromArray(array $value)
    {
        if (isset($value['price'])) {
            $this->setPrice($value['price']);
        }
        if (isset($value['price_effect'])) {
            $this->setPriceEffect($value['price_effect']);
        } else {
            $this->setPriceEffect(PriceInterface::PRICE_COMBINE);
        }

        return $this;
    }
}