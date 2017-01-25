<?php namespace CartLoad\Cart;


class Item {
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

    public function __construct() {}

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
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Item
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId() {
        return $this->product_id;
    }

    /**
     * @param mixed $product_id
     * @return Item
     */
    public function setProductId($product_id) {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQty() {
        return $this->qty;
    }

    /**
     * @param mixed $qty
     * @return Item
     */
    public function setQty($qty) {
        $this->qty = $qty;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getVariations() {
        return $this->variations;
    }

    /**
     * @param int[] $variations
     * @return Item
     */
    public function setVariations($variations) {
        $this->variations = $variations;

        return $this;
    }

    /**
     * @param int $id
     */
    public function addVariation($id) {
        $this->variations []= $id;
    }

    /**
     * @param \DateTime $now
     * @return PriceInterface|null
     */
    public function getPrice(\DateTime $now = null) {
        $price = null;

        //-- Get base price off pricing table
        $prices = $this->getPriceTable()->getPrices($this->qty, $now);
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
                $variation_set_prices = $variation_set->calculatePrice($this->qty, $now);
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
     * @param \CartLoad\Cart\Item $item
     * @return string
     */
    public function getSku(\CartLoad\Cart\Item $item = null, \DateTime $now = null) {
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
                    if ($delimiter === NULL) {
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
                        if ($delimiter === NULL) {
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
                        if ($delimiter === NULL) {
                            $delimiter = $default_delimiter;
                        }

                        if (strlen($result) > 0) {
                            $result = implode($delimiter, [$result, $sku]);
                        }
                        else {
                            $result = $sku;
                        }

                        return $result;
                    }, $sku);
                }
            }
        }

        return $sku;
    }
}