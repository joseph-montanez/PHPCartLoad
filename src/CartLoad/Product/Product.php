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
     * Find the matching product computed combination from cart item
     *
     * @param Item $item
     * @return Combination|null
     */
    public function getCombinationByCartItem(Item $item)
    {
        $cart_item_variations = $item->getVariations();
        if (count($cart_item_variations) === 0) {
            return null;
        }

        //-- Sort to use array equality
        sort($cart_item_variations);

        /** @var Combination $combination */
        foreach ($this->combinations as $combination) {
            $product_combination_variations = $combination->getVariations();
            if ($product_combination_variations === null) {
                continue;
            }
            //-- Sort to use array equality
            sort($product_combination_variations);

            //-- TODO: this should be a configuration option, as to do weak versus strict equality
            if ($cart_item_variations == $product_combination_variations) {
                return $combination;
            }
        }

        return null;
    }

    /**
     * @param $qty
     * @param \DateTime $now
     * @return PriceInterface|null
     */
    public function getCartPrice($qty, \DateTime $now = null)
    {
        //-- If this is a CartItem and the Product has a matching computed combination, then return the price from the
        // combination
        if (count($this->combinations) > 0 && is_object($qty) && $qty instanceof Item) {
            /** @var Item $item */
            $item = $qty;
            $combination = $this->getCombinationByCartItem($item);
            if ($combination) {
                return $combination->getPrice();
            }
        }

        $price = null;

        //-- Get base price off pricing table
        $prices = $this->getPriceTable()->getPrices($qty, $now);
        if (count($prices) > 0) {
            $price = current($prices);
            if ($price instanceof PriceInterface) {
                $price = $price->getPrice();
            }
        }

        //-- Get the configuration price
        if (isset($this->variations)) {
            $prices = [
                'combines' => [],
                'replaces' => [],
            ];
            foreach ($this->getVariations() as $variation_set) {
                $variation_set_prices = $variation_set->calculatePrice($qty, $now);
                if (count($variation_set_prices['combines']) > 0) {
                    $prices['combines'] = array_merge($prices['combines'], $variation_set_prices['combines']);
                }
                if (count($variation_set_prices['replaces']) > 0) {
                    $prices['replaces'] = array_merge($prices['replaces'], $variation_set_prices['replaces']);
                }
            }
            if (count($prices['replaces']) > 0) {
                $price = array_sum($prices['replaces']);
            } else {
                $price += array_sum($prices['combines']);
            }
        }

        return $price;
    }

    /**
     * @return VariationSet[]
     */
    public function getVariations()
    {
        return $this->variations;
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

    /**
     * @param Item $item
     * @return string
     */
    public function getCartSku(Item $item = null, \DateTime $now = null)
    {
        //-- If this is a CartItem and the Product has a matching computed combination, then return the price from the
        // combination
        if (count($this->combinations) > 0) {
            $combination = $this->getCombinationByCartItem($item);
            if ($combination) {
                return $combination->getSku();
            }
        }

        $sku = $this->sku;

        if ($item === null) {
            return $sku;
        }

        //-- Get the configuration price
        if (isset($this->variations)) {
            $default_effect = $this->variations instanceof SkuInterface ? $this->variations->getSkuEffect() : SkuInterface::SKU_END_OF;
            $default_delimiter = $this->variations instanceof SkuInterface ? $this->variations->getSkuDelimiter() : '-';

            $skus = [
                'replaces' => [],
                'starts' => [],
                'ends' => [],
            ];
            foreach ($this->getVariations() as $variation_set) {
                if ($variation_set->hasVariationIds($item->getVariations())) {
                    $variation_set_skus = $variation_set->calculateSkus($item, $now);
                    if (count($variation_set_skus['replaces']) > 0) {
                        $skus['replaces'] = array_merge($skus['replaces'], $variation_set_skus['replaces']);
                    }
                    if (count($variation_set_skus['starts']) > 0) {
                        $skus['starts'] = array_merge($skus['starts'], $variation_set_skus['starts']);
                    }
                    if (count($variation_set_skus['ends']) > 0) {
                        $skus['ends'] = array_merge($skus['ends'], $variation_set_skus['ends']);
                    }
                }
            }

            //-- If the SKU is to replace then use the follow logic.
            if (count($skus['replaces']) > 0) {
                $sku = array_reduce($skus['replaces'], function ($result, $sku_data) use ($default_delimiter) {
                    list($sku, $delimiter) = $sku_data;
                    if ($delimiter === null) {
                        $delimiter = $default_delimiter;
                    }

                    if (strlen($result) > 0) {
                        $result = implode($delimiter, [$result, $sku]);
                    } else {
                        $result = $sku;
                    }

                    return $result;
                }, $sku);
            } else {
                //-- Prepend anything to the beginning of the SKU
                if (count($skus['starts']) > 0) {
                    $sku = array_reduce($skus['starts'], function ($result, $sku_data) use ($default_delimiter) {
                        list($sku, $delimiter) = $sku_data;
                        if ($delimiter === null) {
                            $delimiter = $default_delimiter;
                        }

                        if (strlen($result) > 0) {
                            $result = implode($delimiter, [$sku, $result]);
                        } else {
                            $result = $sku;
                        }

                        return $result;
                    }, $sku);
                }

                //-- Append anything to the beginning of the SKU
                if (count($skus['ends']) > 0) {
                    $sku = array_reduce($skus['ends'], function ($result, $sku_data) use ($default_delimiter) {
                        list($sku, $delimiter) = $sku_data;
                        if ($delimiter === null) {
                            $delimiter = $default_delimiter;
                        }

                        if (strlen($result) > 0) {
                            $result = implode($delimiter, [$result, $sku]);
                        } else {
                            $result = $sku;
                        }

                        return $result;
                    }, $sku);
                }
            }
        }

        return $sku;
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