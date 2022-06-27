<?php

namespace CartLoad\Tests\unit;

use CartLoad\Cart\Item;
use CartLoad\Product\Product;

class WeightTest extends \Codeception\Test\Unit
{

    public function testWeight()
    {
        $shirt = Product::make([
            'id' => 1,
            'name' => 'Shirt',
            'sku' => 'shirt',
            'weight' => 1.0,
            'price' => [
                ['min_qty' => 1, 'max_qty' => 9, 'price' => 4.95],
                ['min_qty' => 10, 'max_qty' => 19, 'price' => 3.95],
            ],
            'variations' => [
                [
                    'id' => 1,
                    'name' => 'Color',
                    'required' => true,
                    'items' => [
                        ['id' => 1, 'name' => 'Red', 'price' => 0.5, 'sku' => 'r'],
                        ['id' => 2, 'name' => 'Blue', 'price' => 0.4, 'sku' => 'b'],
                        ['id' => 3, 'name' => 'Green', 'price' => 0.6, 'sku' => 'g'],
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Size',
                    'required' => true,
                    'items' => [
                        ['id' => 4, 'name' => 'Small', 'price' => 1.0, 'sku' => 's', 'weight' => 0.1],
                        ['id' => 5, 'name' => 'Medium', 'price' => 1.1, 'sku' => 'm', 'weight' => 0.4],
                        ['id' => 6, 'name' => 'Large', 'price' => 1.2, 'sku' => 'l', 'weight' => 1.0],
                    ]
                ],
            ]
        ]);

        $this->assertEquals(1.0, $shirt->getWeight());

        $variationSet = $shirt->getVariations()[1];
        $variation = $variationSet->getItems()[0];
        $this->assertEquals(0.1, $variation->getWeight());

        //-- Blue Medium Shirt
        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1, //Shirt product ID
            'qty'        => 1,
            'variations' => [2, 5] // Blue, Medium
        ]);
    }

}