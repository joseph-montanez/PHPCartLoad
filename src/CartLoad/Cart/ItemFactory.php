<?php

namespace CartLoad\Cart;


class ItemFactory
{

    /**
     * @param array $data
     * @param Item $item
     * @return Item
     */
    public function make(array $data, Item $item = null)
    {
        if (is_null($item)) {
            $item = new Item();
        }

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'id':
                    $item->setId($value);
                    break;
                case 'product_id':
                    $item->setProductId($value);
                    break;
                case 'qty':
                    $item->setQty($value);
                    break;
                case 'variations':
                    $item->setVariations($value);
                    break;
            }
        }

        return $item;
    }
}