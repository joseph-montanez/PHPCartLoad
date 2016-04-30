<?php namespace CartLoad\Product;

use CartLoad\Product\Option\ItemSet;
use CartLoad\Product\Price\Feature\PriceInterface;

class Item {
	/** @var int|string $id The identifier of the item, can be an int or UUID */
	protected $id;
	/** @var string $id The name of the item */
	protected $name;
	/** @var string $id The description of the item */
	protected $description;
	/** @var string $id The stock keeping init of the item */
	protected $sku;
    /** @var PriceTable $price_table The prices for this item based on qty, date, and more */
    protected $price_table;
    /** @var ItemSet $options The prices for this item based on qty, date, and more */
    protected $options;

    public function __construct(array $data = []) {
        if (count($data) > 0) {
            $this->fromArray($data);
        } else {
            $this->options = [];
        }
    }

    /**
     * @return int|string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int|string $id
     * @return Item
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Item
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Item
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getSku() {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return Item
     */
    public function setSku($sku) {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return PriceTable
     */
    public function getPriceTable() {
        return $this->price_table;
    }

    /**
     * @param PriceTable $price_table
     * @return Item
     */
    public function setPriceTable($price_table) {
        $this->price_table = $price_table;

        return $this;
    }

    /**
     * @return ItemSet[]
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * @param ItemSet $options
     * @return Item
     */
    public function setOptions($options) {
        $this->options = $options;

        return $this;
    }

    public function fromArray(array $data) {
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'id':
                    $this->setId($value);
                    break;
                case 'name':
                    $this->setName($value);
                    break;
                case 'description':
                    $this->setDescription($value);
                    break;
                case 'sku':
                    $this->setSku($value);
                    break;
                case 'price':
                    $price_table = new PriceTable();
                    $price_table->fromArray($value);
                    $this->setPriceTable($price_table);
                    break;
                case 'options':
                    $option_sets = array_map(function ($option_set) { return new ItemSet($option_set); }, $value);
                    $this->setOptions($option_sets);

            }
        }
    }

    /**
     * @param $qty
     * @param \DateTime $now
     * @return PriceInterface|null
     */
    public function getPrice($qty, \DateTime $now = null) {
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
        if (isset($this->options)) {
            $prices = [
                'combines' => [],
                'replaces' => [],
            ];
            foreach ($this->getOptions() as $option_set) {
                $option_set_prices = $option_set->calculatePrice($qty, $now);
                if (count($option_set_prices['combines']) > 0) {
                    $prices['combines'] = array_merge($prices['combines'], $option_set_prices['combines']);
                }
                if (count($option_set_prices['replaces']) > 0) {
                    $prices['replaces'] = array_merge($prices['replaces'], $option_set_prices['replaces']);
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
}