<?php namespace CartLoad\Cart;


use CartLoad\Cart\Item\SkuProcessor;
use CartLoad\Product\Combination;
use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Price\SimpleFactory;
use CartLoad\Product\Product;

class Item
{
    use Errors;

    /**
     * @var string|int
     */
    protected $id;
    /**
     * @var string|int
     */
    protected $product_id;
    /**
     * @var int
     */
    protected $qty;
    /**
     * @var int[]
     */
    protected $variations;

    public function __construct()
    {
    }

    /**
     * @param $data
     * @return \CartLoad\Cart\Item
     */
    public static function make($data)
    {
        return (new ItemFactory())->make($data);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Item
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $product_id
     * @return Item
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param mixed $qty
     * @return Item
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getVariations()
    {
        return $this->variations;
    }

    /**
     * @param int[] $variations
     * @return Item
     */
    public function setVariations($variations)
    {
        $this->variations = $variations;

        return $this;
    }

    /**
     * Add the ID of the variation item, can be a string or integer
     * @param int|string $id
     */
    public function addVariation($id)
    {
        $this->variations [] = $id;
    }


    /**
     * Find the matching product computed combination from cart item
     *
     * @param Product $product
     * @return Combination|null
     */
    public function getProductCombination(Product $product)
    {
        $cart_item_variations = $this->getVariations();

        //-- Sort to use array equality
        sort($cart_item_variations);

        /** @var Combination $combination */
        foreach ($product->getCombinations() as $combination) {
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
     * @param Product $product
     * @param \DateTime $now
     * @return PriceInterface|null
     * @internal param $qty
     */
    public function getPriceInterface(Product $product, \DateTime $now = null)
    {
        //-- If this is a CartItem and the Product has a matching computed combination, then return the price from the
        // combination
        if (count($product->getCombinations()) > 0) {
            $combination = $this->getProductCombination($product);
            if ($combination) {
                return $combination->getPrice();
            }
        }

        $price = null;

        //-- Get base price off pricing table
        $prices = $product->getPriceTable()->getPrices($this, $now);
        if (count($prices) > 0) {
            $price = current($prices);
            if ($price instanceof PriceInterface) {
                $price = $price->getPrice();
            }
        }

        //-- Get the configuration price
        if (count($product->getVariations()) > 0) {
            $prices = [
                'combines' => [],
                'replaces' => [],
            ];
            foreach ($product->getVariations() as $variation_set) {
                $variation_set_prices = $variation_set->calculatePrice($this, $now);
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

        return (new SimpleFactory())->make($price);
    }

    /**
     * @param \CartLoad\Product\Product $product
     * @param \DateTime|null            $now
     *
     * @return float
     */
    public function getPrice(Product $product, \DateTime $now = null): float
    {
        $price = $this->getPriceInterface($product, $now);
        return $price->getPrice();
    }


    /**
     * @param Product $product
     * @param \DateTime $now
     * @return string
     */
    public function getSku(Product $product, \DateTime $now = null)
    {
        $processor = new SkuProcessor();
        return $processor->getSku($this, $product, $now);
    }
}