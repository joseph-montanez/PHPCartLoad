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
    protected $options;

    public function __construct(array $data = []) {
        if ($data !== null && is_array($data)) {
            $this->fromArray($data);
        }
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
    public function getOptions() {
        return $this->options;
    }

    /**
     * @param int[] $options
     * @return Item
     */
    public function setOptions($options) {
        $this->options = $options;

        return $this;
    }

    public function addOption(int $id) {
        $this->options []= $id;
    }

    /**
     * @param array $data
     * @return self
     */
    public function fromArray(array $data) {
        if (isset($data['id'])) {
            $this->setId($data['id']);
        }
        if (isset($data['product_id'])) {
            $this->setProductId($data['product_id']);
        }
        if (isset($data['qty'])) {
            $this->setQty($data['qty']);
        }
        if (isset($data['options'])) {
            $this->setOptions($data['options']);
        }

        return $this;
    }
}