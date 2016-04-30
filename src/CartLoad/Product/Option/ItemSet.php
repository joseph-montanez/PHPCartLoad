<?php namespace CartLoad\Product\Option;


use CartLoad\Product\Option\Feature\SkuInterface;

class ItemSet implements SkuInterface {
    use SkuTrait;

    protected $id;
    protected $name;
    protected $required;
    protected $order;
    /**
     * @var Item[]
     */
    protected $items;

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
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Item
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequired() {
        return $this->required;
    }

    /**
     * @param mixed $required
     * @return Item
     */
    public function setRequired($required) {
        $this->required = $required;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * @param mixed $order
     * @return Item
     */
    public function setOrder($order) {
        $this->order = $order;

        return $this;
    }

}