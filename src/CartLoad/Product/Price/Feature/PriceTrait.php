<?php namespace CartLoad\Product\Price\Feature;

trait PriceTrait {
	/** @var float $price */
	protected $price;

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
}