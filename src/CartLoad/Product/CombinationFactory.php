<?php
/**
 * Created by PhpStorm.
 * User: josephmontanez
 * Date: 1/23/2017
 * Time: 11:26 PM
 */

namespace CartLoad\Product;


use CartLoad\Product\Price\SimpleFactory;

class CombinationFactory
{

    /**
     * @param array $data
     * @param Combination $combination
     * @return Combination
     */
    public function make(array $data, Combination $combination = null)
    {
        if (is_null($combination)) {
            $combination = new Combination();
        }

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'id':
                    $combination->setId($value);
                    break;
                case 'variations':
                    $combination->setVariations($value);
                    break;
                case 'price':
                    $combination->setPrice((new SimpleFactory())->make($value));
                    break;
                case 'sku':
                    $combination->setSku($value);
                    break;
            }
        }

        return $combination;
    }
}