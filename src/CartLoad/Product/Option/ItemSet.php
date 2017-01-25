<?php namespace CartLoad\Product\Option;

use CartLoad\Product\Option\Feature\SkuInterface;
use CartLoad\Product\Option\Feature\SkuTrait;
use CartLoad\Product\Feature\PriceInterface;

class ItemSet implements SkuInterface
{
    use SkuTrait;

    protected $id;
    protected $name;
    protected $required;
    protected $order;
    /**
     * @var Item[]
     */
    protected $items;

    public function __construct(array $data = [])
    {
        if (count($data) > 0) {
            $this->fromArray($data);
        } else {
            $this->setSkuDelimiter('-');
            $this->setSkuEffect(SkuInterface::SKU_END_OF);
        }
    }

    /**
     * @param $value
     * @return $this
     */
    public function fromArray($value)
    {
        if (isset($value['id'])) {
            $this->setId($value['id']);
        }
        if (isset($value['name'])) {
            $this->setName($value['name']);
        }
        if (isset($value['required'])) {
            $this->setRequired($value['required']);
        }
        if (isset($value['order'])) {
            $this->setOrder($value['order']);
        }
        if (isset($value['items'])) {
            $items = array_map(function ($item) {
                return Item::make($item);
            }, $value['items']);
            $this->setItems($items);
        }
        if (isset($value['sku'])) {
            if (is_array($value['sku'])) {
                if (isset($value['sku']['sku'])) {
                    $this->setSku($value['sku']['sku']);
                }
                if (isset($value['sku']['delimiter'])) {
                    $this->setSkuDelimiter($value['sku']['delimiter']);
                } else {
                    $this->setSkuDelimiter('-');
                }
                if (isset($value['sku']['effect'])) {
                    $this->setSkuEffect($value['sku']['effect']);
                } else {
                    $this->setSkuEffect(SkuInterface::SKU_END_OF);
                }
            } else {
                $this->setSku($value['sku']);
                $this->setSkuDelimiter('-');
                $this->setSkuEffect(SkuInterface::SKU_END_OF);
            }
        } else {
            $this->setSkuDelimiter('-');
            $this->setSkuEffect(SkuInterface::SKU_END_OF);
        }

        return $this;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param mixed $required
     * @return Item
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     * @return Item
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @param $qty
     * @param \DateTime|NULL $now
     * @return array
     */
    public function calculatePrice($qty, \DateTime $now = null)
    {
        if ($now === null) {
            $now = new \DateTime();
        }

        if (is_object($qty) && $qty instanceof \CartLoad\Cart\Item) {
            $cart_item = $qty;
            $qty = $cart_item->getQty();
            $option_ids = $cart_item->getOptions();

            $prices = [
                'replaces' => [],
                'combines' => [],
            ];

            foreach ($this->getItems() as $item) {
                foreach ($option_ids as $option_id) {
                    if ($item->getId() == $option_id) {
                        if ($item->getPriceEffect() === PriceInterface::PRICE_COMBINE) {
                            $prices['combines'] [] = $item->getPrice();
                        } else {
                            if ($item->getPriceEffect() === PriceInterface::PRICE_REPLACE_ALL) {
                                $prices['replaces'] [] = $item->getPrice();
                            }
                        }
                    }
                }
            }

            return $prices;
        }

        return [];
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     * @return ItemSet
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    public function calculateSkus($qty, \DateTime $now = null)
    {
        if ($now === null) {
            $now = new \DateTime();
        }

        if (is_object($qty) && $qty instanceof \CartLoad\Cart\Item) {
            $cart_item = $qty;
            $qty = $cart_item->getQty();
            $option_ids = $cart_item->getOptions();

            $skus = [
                'replaces' => [],
                'starts' => [],
                'ends' => [],
            ];

            foreach ($this->getItems() as $item) {
                foreach ($option_ids as $option_id) {
                    if ($item->getId() == $option_id) {
                        $sku_effect = $item->getSkuEffect() ?: $this->getSkuEffect();
                        $sku_delimiter = $item->getSkuDelimiter() ?: $this->getSkuDelimiter();

                        if ($sku_effect === SkuInterface::SKU_REPLACE_ALL) {
                            $skus['replaces'] [] = [$item->getSku(), $sku_delimiter];
                        } else {
                            if ($sku_effect === SkuInterface::SKU_START_OF) {
                                $skus['starts'] [] = [$item->getSku(), $sku_delimiter];
                            } else {
                                if ($sku_effect === SkuInterface::SKU_END_OF) {
                                    $skus['ends'] [] = [$item->getSku(), $sku_delimiter];
                                }
                            }
                        }
                    }
                }
            }

            return $skus;
        }

        return [];
    }

    /**
     * @param $getOptions
     * @return bool
     */
    public function hasOptionIds($getOptions)
    {
        foreach ($this->items as $item) {
            if (in_array($item->getId(), $getOptions)) {
                return true;
            }
        }

        return false;
    }
}