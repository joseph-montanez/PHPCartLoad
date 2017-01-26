<?php namespace CartLoad\Product\Variation;

use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\PriceTrait;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Feature\SkuTrait;

class Variation implements SkuInterface, PriceInterface
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
        if (isset($value['sku'])) {
            if (is_array($value['sku'])) {
                if (isset($value['sku']['sku'])) {
                    $this->setSku($value['sku']['sku']);
                }
                if (isset($value['sku']['delimiter'])) {
                    $this->setSkuDelimiter($value['sku']['delimiter']);
                }
                if (isset($value['sku']['effect'])) {
                    $this->setSkuEffect($value['sku']['effect']);
                }
            } else {
                $this->setSku($value['sku']);
                $this->setSkuDelimiter(null);
                $this->setSkuEffect(null);
            }
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
     * @return Variation
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
     * @return Variation
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
     * @return Variation
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
     * @return Variation
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

}