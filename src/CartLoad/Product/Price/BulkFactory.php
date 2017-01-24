<?php
/**
 * Created by PhpStorm.
 * User: josephmontanez
 * Date: 5/5/16
 * Time: 7:18 AM
 */

namespace CartLoad\Product\Price;


class BulkFactory
{

    /**
     * @param array $price
     * @param null $bulk
     * @return Bulk
     */
    public function make(array $price, $bulk = null)
    {
        if (is_null($bulk)) {
            $bulk = new Bulk();
        }

        if (isset($price['price'])) {
            $bulk->setPrice($price['price']);
        }

        $this->minMaxQtyFromArray($bulk, $price);
        $this->minMaxDateFromArray($bulk, $price);

        return $bulk;
    }

    public function minMaxQtyFromArray(Bulk $bulk, $price)
    {
        if (isset($price['min_qty'])) {
            if ($price['min_qty'] === -1 || $price['min_qty'] === false || $price['min_qty'] === null) {
                $bulk->setNoMinimumQtyLimit(true);
            } else {
                $bulk->setMinQty((int)$price['min_qty']);
                $bulk->setNoMinimumQtyLimit(false);
            }
        } else {
            $bulk->setNoMinimumQtyLimit(true);
        }
        if (isset($price['max_qty'])) {
            if ($price['max_qty'] === -1 || $price['max_qty'] === false || $price['max_qty'] === null) {
                $bulk->setNoMaximumQtyLimit(true);
            } else {
                $bulk->setMaxQty((int)$price['max_qty']);
                $bulk->setNoMaximumQtyLimit(false);
            }
        } else {
            $bulk->setNoMaximumQtyLimit(true);
        }
    }

    public function minMaxDateFromArray(Bulk $bulk, $price)
    {
        if (isset($price['min_date'])) {
            if ($price['min_date'] === -1 || $price['min_date'] === false || $price['min_date'] === null) {
                $bulk->setNoMinimumDateLimit(true);
            } else {
                $bulk->setMinDate(new \DateTime($price['min_date']));
                $bulk->setNoMinimumDateLimit(false);
            }
        } else {
            $bulk->setNoMinimumDateLimit(true);
        }

        if (isset($price['max_date'])) {
            if ($price['max_date'] === -1 || $price['max_date'] === false || $price['max_date'] === null) {
                $bulk->setNoMaximumDateLimit(true);
            } else {
                $bulk->setMaxDate(new \DateTime($price['max_date']));
                $bulk->setNoMaximumDateLimit(false);
            }
        } else {
            $bulk->setNoMaximumDateLimit(true);
        }
    }
}