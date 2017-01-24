<?php
/**
 * Created by PhpStorm.
 * User: josephmontanez
 * Date: 1/23/2017
 * Time: 11:19 PM
 */

namespace CartLoad\Product;


use CartLoad\Product\Feature\PriceInterface;

class Combination
{
    /** @var int|string $id The identifier of the item, can be an int or UUID */
    protected $id;
    /** @var int[]|string[] $id An array of variation identifiers, can be an int or UUID */
    protected $variations;
    /** @var PriceInterface The resulting price of the variation */
    protected $price;
    /** @var string The resulting stock keeping unit of the variation */
    protected $sku;

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     * @return Combination
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \int[]|\string[]
     */
    public function getVariations()
    {
        return $this->variations;
    }

    /**
     * @param \int[]|\string[] $variations
     * @return Combination
     */
    public function setVariations($variations)
    {
        $this->variations = $variations;
        return $this;
    }

    /**
     * @return PriceInterface
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param PriceInterface $price
     * @return Combination
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return Combination
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

}