<?php namespace CartLoad\Product;

use CartLoad\Cart\Item;
use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Variation\VariationSet;

class Product
{
    /** @var int|string $id The identifier of the item, can be an int or UUID */
    protected $id;
    /** @var string $id The name of the item */
    protected $name;
    /** @var string $id The description of the item */
    protected $description;
    /** @var string $id The stock keeping init of the item */
    protected $sku;
    /** @var float $weight The weight of the item */
    protected $weight;
    /** @var PriceTable $price_table The prices for this item based on qty, date, and more */
    protected $price_table;
    /** @var VariationSet[] $variations The prices for this item based on qty, date, and more */
    protected $variations = [];
    /** @var Combination[] $combinations The precomputed combinations with sku, price options */
    protected $combinations = [];

    public function __construct()
    {
    }

    /**
     * Use the product factory method to make a product based off an array
     *
     * @param array $data
     * @return Product
     */
    public static function make(array $data = [])
    {
        return (new ProductFactory())->make($data);
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return Product
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get the price of the product without a cart item instance, only use this if you don't want to use the cart
     *
     * @param int $qty
     * @return float
     */
    public function getPrice($qty = 1)
    {
        $price_list = $this->getPriceTable()->getPrices($qty);
        if (count($price_list) > 0) {
            return current($price_list)->getPrice();
        } else {
            return 0.00;
        }
    }

    /**
     * @return PriceTable
     */
    public function getPriceTable()
    {
        return $this->price_table;
    }

    /**
     * @param PriceTable $price_table
     * @return Product
     */
    public function setPriceTable($price_table)
    {
        $this->price_table = $price_table;

        return $this;
    }

    /**
     * @return VariationSet[]
     */
    public function getVariations()
    {
        return isset($this->variations) ? $this->variations : [];
    }

    /**
     * @param VariationSet[] $variations
     * @return Product
     */
    public function setVariations($variations)
    {
        $this->variations = $variations;

        return $this;
    }

    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return Combination[]
     */
    public function getCombinations()
    {
        return $this->combinations;
    }

    /**
     * @param Combination[] $combinations
     * @return Product
     */
    public function setCombinations($combinations)
    {
        $this->combinations = $combinations;
        return $this;
    }

}