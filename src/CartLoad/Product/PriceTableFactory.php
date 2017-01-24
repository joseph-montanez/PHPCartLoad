<?php namespace CartLoad\Product;


use CartLoad\Product\Price\BulkFactory;
use CartLoad\Product\Price\SimpleFactory;

class PriceTableFactory
{

    /**
     * @param $prices
     * @return \CartLoad\Product\PriceTable
     */
    public function make($prices)
    {
        $price_table = new PriceTable();
        if (is_array($prices)) {
            foreach ($prices as $key => $price) {
                if (is_float($price) || is_double($price)) {
                    $price_table->addPrice((new SimpleFactory())->make($price));
                } else {
                    $price_type = current(array_keys($price));
                    $price_value = $price[$price_type];

                    if ($price_type === 'Simple') {
                        $price_table->addPrice((new SimpleFactory())->make($price_value));
                    } else {
                        if ($price_type === 'Bulk') {
                            $price_table->addPrice((new BulkFactory())->make($price_value));
                        } else {
                            if (class_exists($price_type)) {
                                $price_table->addPrice((new $price_type())->make($price[$price_type]));
                            } else {
                                $price_table->addPrice((new BulkFactory())->make($price));
                            }
                        }
                    }
                }
            }
        } else {
            if (is_float($prices) || is_double($prices)) {
                $price_table->addPrice((new SimpleFactory())->make($prices));
            }
        }

        return $price_table;
    }
}