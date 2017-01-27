<?php namespace CartLoad\Product\Option;

use CartLoad\Cart\ItemFactory;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Feature\SkuTrait;
use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\PriceTrait;

class Item implements SkuInterface, PriceInterface
{
    use SkuTrait, PriceTrait;

    protected $id;
    protected $name;
    protected $required;
    protected $order;

    public function __construct(array $data = [])
    {
        if (count($data) > 0) {
            $this->fromArray($data);
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
        if (isset($value['price'])) {
            $this->setPrice($value['price']);
        }
        if (isset($value['price_effect'])) {
            $this->setPriceEffect($value['price_effect']);
        } else {
            $this->setPriceEffect(PriceInterface::PRICE_COMBINE);
        }
        if (isset($value['items'])) {
            $this->setItems(array_map(function ($item) {
                return Item::make($item);
            }, $value['items']));
        }
        $this->skuFromArray($value);

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

}