<?php
namespace CartLoad\Product\Price;

use CartLoad\Product\Price\Feature\MinMaxDateTrait;
use CartLoad\Product\Price\Feature\PriceInterface;
use CartLoad\Product\Price\Feature\MinMaxQtyTrait;
use CartLoad\Product\Price\Feature\PriceTrait;
use CartLoad\Product\Price\Feature\MinMaxQtyInterface;
use CartLoad\Product\Price\Feature\MinMaxDateInterface;


class Bulk implements PriceInterface, MinMaxQtyInterface, MinMaxDateInterface {
	use PriceTrait, MinMaxQtyTrait, MinMaxDateTrait;

    /**
     * @param array $price
     * @return self
     */
    public function fromArray(array $price) {
        if (isset($price['price'])) {
            $this->setPrice($price['price']);
        }

        $this->minMaxQtyFromArray($price);
        $this->minMaxDateFromArray($price);

        return $this;
    }

    private function minMaxQtyFromArray($price) {
        if (isset($price['min_qty'])) {
            if ($price['min_qty'] === -1 || $price['min_qty'] === false || $price['min_qty'] === null) {
                $this->setNoMinimumQtyLimit(true);
            } else {
                $this->setMinQty((int) $price['min_qty']);
                $this->setNoMinimumQtyLimit(false);
            }
        } else {
            $this->setNoMinimumQtyLimit(true);
        }
        if (isset($price['max_qty'])) {
            if ($price['max_qty'] === -1 || $price['max_qty'] === false || $price['max_qty'] === null) {
                $this->setNoMaximumQtyLimit(true);
            } else {
                $this->setMaxQty((int) $price['max_qty']);
                $this->setNoMaximumQtyLimit(false);
            }
        } else {
            $this->setNoMaximumQtyLimit(true);
        }
    }

    private function minMaxDateFromArray($price) {
        if (isset($price['min_date'])) {
            if ($price['min_date'] === -1 || $price['min_date'] === false || $price['min_date'] === null) {
                $this->setNoMinimumDateLimit(true);
            } else {
                $this->setMinDate(new \DateTime($price['min_date']));
                $this->setNoMinimumDateLimit(false);
            }
        } else {
            $this->setNoMinimumDateLimit(true);
        }

        if (isset($price['max_date'])) {
            if ($price['max_date'] === -1 || $price['max_date'] === false || $price['max_date'] === null) {
                $this->setNoMaximumDateLimit(true);
            } else {
                $this->setMaxDate(new \DateTime($price['max_date']));
                $this->setNoMaximumDateLimit(false);
            }
        } else {
            $this->setNoMaximumDateLimit(true);
        }
    }
}