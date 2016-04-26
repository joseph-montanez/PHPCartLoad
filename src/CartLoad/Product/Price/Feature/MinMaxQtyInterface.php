<?php namespace CartLoad\Product\Price\Feature;


interface MinMaxQtyInterface {
    /**
     * @return int
     */
    public function getMinQty();

    /**
     * @param int $min
     * @return self
     */
    public function setMinQty(int $min);

    /**
     * @return int
     */
    public function getMaxQty();

    /**
     * @param int $max
     * @return self
     */
    public function setMaxQty(int $max);

    /**
     * @return boolean
     */
    public function isNoMinimumQtyLimit();

    /**
     * @param boolean $no_minimum_limit
     * @return self
     */
    public function setNoMinimumQtyLimit(bool $no_minimum_limit);

    /**
     * @return boolean
     */
    public function isNoMaximumQtyLimit();

    /**
     * @param boolean $no_maximum_limit
     * @return self
     */
    public function setNoMaximumQtyLimit(bool $no_maximum_limit);

    /**
     * @param int $qty
     * @return bool
     */
    public function inMinMaxQtyRange(int $qty);

}