<?php namespace CartLoad\Product;


use CartLoad\Product\Price\Simple;
use CartLoad\Product\Price\Bulk;
use CartLoad\Product\Price\Feature\MinMaxDateInterface;
use CartLoad\Product\Price\Feature\MinMaxQtyInterface;
use CartLoad\Product\Price\Feature\PriceInterface;

class PriceTable {
    /**
     * A list of prices
     * @var array $prices
     */
    protected $prices;

    /**
     * If this will allow multiple prices to be returned or not
     * @var bool $undefined_behavior
     */
    protected $undefined_behavior;

    /**
     * Set of methods that return true or false for a price match
     * @var array
     */
    protected $qualifiers;

    /**
     * PriceTable constructor.
     * @param PriceInterface[] $prices
     */
    public function  __construct(array $prices = [])
    {
        $this->prices = $prices;
        $this->setUndefinedBehavior(false);
        $this->qualifiers = [];
        $this->addDefaultQualifiers();
    }

    /**
     * Adds the default price qualifiers
     */
    public function addDefaultQualifiers()
    {
        $this->addQualifier(function ($price, $args) {
            list($qty, $now) = $args;

            return $price instanceof MinMaxDateInterface && $price->inMinMaxDateRange($now);
        });

        $this->addQualifier(function ($price, $args) {
            list($qty, $now) = $args;

            return $price instanceof MinMaxQtyInterface && $price->inMinMaxQtyRange($qty);
        });

        $this->addQualifier(function ($price, $args) {
            return !$price instanceof MinMaxDateInterface && !$price instanceof MinMaxQtyInterface;
        });
    }

    /**
     * @param $fn Adds a function to the list of qualifiers of a price
     */
    public function addQualifier($fn) {
        $this->qualifiers [] = $fn;
    }

    /**
     * Add a price, it must at least have the price interface to get pricing from.
     * @param PriceInterface $price
     */
    public function addPrice(PriceInterface $price) {
        $this->prices [] = $price;
    }

    /**
     * @return bool
     */
    public function getUndefinedBehavior()
    {
        return $this->undefined_behavior;
    }

    /**
     * @param bool $undefined_behavior
     * @return PriceTable
     */
    public function setUndefinedBehavior(bool $undefined_behavior)
    {
        $this->undefined_behavior = $undefined_behavior;
        return $this;
    }



    /**
     * Return a list of prices that match the quantity and date. This will return multiple prices, if undefined debavori so that a developer
     * layer the results, so if they want to implement member based pricing, the existing code here can be used, and
     * they will filter the results. So either extend this class and override this method, or not.
     *
     * @param int $qty
     * @param \DateTime|NULL $now
     * @return PriceInterface[]
     */
    public function getPrices(int $qty, \DateTime $now = NULL) {
        if ($now === NULL) {
            $now = new \DateTime();
        }

        $params = func_get_args();

        $price_list = array_reduce($this->prices, function ($result, $price) use ($params) {
            $matches = array_reduce($this->qualifiers, function ($result, $qualifier) use ($price, $params) {
                return $qualifier($price, $params) ? $result + 1 : $result;
            });

            if ($matches > 0) {
                $result [] = ['matches' => $matches, 'price' => $price];
            }

            return $result;
        }, []);

        if (!$this->undefined_behavior) {
            $price_list = array_reduce($price_list, function ($result, $price) {
                if (count($result) === 0) {
                    $result = [$price];
                } else if ($price['matches'] > $result[0]['matches']) {
                    $result = [$price];
                }

                return $result;
            }, []);
        }

        return array_map(function ($result) {
            return $result['price'];
        }, $price_list);
    }

    /**
     * @param array|float $prices
     * @return $this
     */
    public function fromArray($prices) {
        if (is_array($prices)) {
            foreach ($prices as $key => $price) {
                if (is_float($price) || is_double($price)) {
                    $this->addPrice(new Simple($price));
                } else {
                    $price_type = current(array_keys($price));
                    $price_value = $price[$price_type];

                    if ($price_type === 'Simple') {
                        $this->addPrice(new Simple($price_value));
                    }
                    else if ($price_type === 'Bulk') {
                        $this->addPrice((new Bulk())->fromArray($price_value));
                    }
                    else {
                        //--TODO: Support Custom Classes, than fall back to bulk pricing
                        $this->addPrice((new Bulk())->fromArray($price));
                    }
                }
            }
        } else if (is_float($prices) || is_double($prices)) {
            $this->addPrice(new Simple($prices));
        }

        return $this;
    }
}