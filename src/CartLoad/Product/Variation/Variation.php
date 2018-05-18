<?php namespace CartLoad\Product\Variation;

use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\PriceTrait;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Feature\SkuTrait;
use CartLoad\Product\Feature\WeightInterface;
use CartLoad\Product\Feature\WeightTrait;

class Variation implements SkuInterface, PriceInterface, WeightInterface
{
    use SkuTrait, PriceTrait, WeightTrait;

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
        $this->priceFromArray($value);
        $this->weightFromArray($value);
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