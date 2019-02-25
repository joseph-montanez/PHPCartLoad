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

    /**
     * @param array $value
     */
    public function skuFromArray(array $value)
    {
        if (isset($value['sku'])) {
            if (is_array($value['sku'])) {
                if (isset($value['sku']['sku'])) {
                    $this->setSku($value['sku']['sku']);
                }
                if (in_array('delimiter', array_keys($value['sku']))) {
                    $this->setSkuDelimiter($value['sku']['delimiter']);
                } else {
                    $this->setSkuDelimiter('-');
                }
                if (in_array('effect', array_keys($value['sku']))) {
                    $this->setSkuEffect($value['sku']['effect']);
                } else {
                    $this->setSkuEffect(SkuInterface::SKU_END_OF);
                }
            } else {
                $this->setSku($value['sku']);
                $this->setSkuDelimiter(null);
                $this->setSkuEffect(null);
            }
        } else {
            $this->setSkuDelimiter('-');
            $this->setSkuEffect(SkuInterface::SKU_END_OF);
        }
    }
}
