<?php namespace CartLoad\Product\Price\Feature;

trait PriceTrait {
	/** @var float $price */
	protected $price;

	/** @var int $price_effect */
	protected $price_effect;

	/**
	 * @return float
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param float $price
	 * @return self
	 */
	public function setPrice(float $price) {
		$this->price = $price;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPriceEffect() {
		return $this->price_effect;
	}

	/**
	 * @param int $price_effect
	 * @return PriceTrait
	 */
	public function setPriceEffect($price_effect) {
		$this->price_effect = $price_effect;

		return $this;
	}
}