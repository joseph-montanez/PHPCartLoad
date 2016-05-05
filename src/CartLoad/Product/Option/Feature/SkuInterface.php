<?php namespace CartLoad\Product\Option\Feature;


interface SkuInterface {
    const SKU_REPLACE_ALL = 0;
    const SKU_START_OF = 1;
    const SKU_END_OF = 2;

    /**
     * @return mixed
     */
    public function getSku();

    /**
     * @param mixed $sku
     * @return self
     */
    public function setSku($sku);

    /**
     * @return mixed
     */
    public function getSkuDelimiter();

    /**
     * @param mixed $sku_delimiter
     * @return self
     */
    public function setSkuDelimiter($sku_delimiter);

    /**
     * @return mixed
     */
    public function getSkuEffect();

    /**
     * @param mixed $sku_effect
     * @return self
     */
    public function setSkuEffect($sku_effect);
}