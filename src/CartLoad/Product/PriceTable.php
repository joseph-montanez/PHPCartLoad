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
     * PriceTable constructor.
     * @param PriceInterface[] $prices
     */
    public function  __construct(array $prices = []) {
        $this->prices = $prices;
    }

    /**
     * Add a price, it must at least have the price interface to get pricing from.
     * @param PriceInterface $price
     */
    public function addPrice(PriceInterface $price) {
        $this->prices [] = $price;
    }

    /**
     * Return a list of prices that match the quantity and date. This will return multiple prices so that a developer
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

        $price_list = array_reduce($this->prices, function ($result, $price) use ($qty, $now) {
            if ($price instanceof MinMaxDateInterface && $price instanceof MinMaxQtyInterface && $price->inMinMaxDateRange($now) && $price->inMinMaxQtyRange($qty)) {
                $result [] = $price;
            }
            //-- There is no qty range, but there is a date range to compare the date
            else if ($price instanceof MinMaxDateInterface && !$price instanceof MinMaxQtyInterface && $price->inMinMaxDateRange($now)) {
                $result [] = $price;
            }
            //-- There is no date or qty range, to compare so just assume its a valid price to return
            else if (!$price instanceof MinMaxDateInterface && !$price instanceof MinMaxQtyInterface) {
                $result [] = $price;
            }

            return $result;
        }, []);

        //-- If there are multiple prices then no need have simple pricing involved
        if (count($price_list) > 1) {
            $price_list = array_values(array_filter($price_list, function (PriceInterface $price) {
                return !($price instanceOf Simple);
            }));
        }

        return $price_list;
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