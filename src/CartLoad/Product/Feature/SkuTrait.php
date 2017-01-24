<?php namespace CartLoad\Product\Feature;


trait SkuTrait
{
    protected $sku;
    protected $sku_delimiter;
    protected $sku_effect;

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return self
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSkuDelimiter()
    {
        return $this->sku_delimiter;
    }

    /**
     * @param mixed $sku_delimiter
     * @return self
     */
    public function setSkuDelimiter($sku_delimiter)
    {
        $this->sku_delimiter = $sku_delimiter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSkuEffect()
    {
        return $this->sku_effect;
    }

    /**
     * @param mixed $sku_effect
     * @return self
     */
    public function setSkuEffect($sku_effect)
    {
        $this->sku_effect = $sku_effect;

        return $this;
    }
}