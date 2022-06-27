<?php

namespace CartLoad\Tests\unit;

use CartLoad\Cart\Item;
use CartLoad\Product\ProductFactory;
use Tests\Support\UnitTester;

class CombinationsTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testGeneral()
    {
        $shirt = (new ProductFactory)->make([
            'id' => 1,
            'name' => 'Shirt',
            'sku' => 'shirt',
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
                        ['id' => 4, 'name' => 'Small', 'price' => 1.0, 'sku' => 's'],
                        ['id' => 5, 'name' => 'Medium', 'price' => 1.1, 'sku' => 'm'],
                        ['id' => 6, 'name' => 'Large', 'price' => 1.2, 'sku' => 'l'],
                    ]
                ],
            ],
            'combinations' => [
                //-- Blue Medium Shirt
                [
                    'id' => 1,
                    'variations' => [2, 5],
                    'price' => 7.00,
                    'sku' => 'shirt-blue-media',
                ]
            ]
        ]);

        //-- Blue Medium Shirt
        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1, //Shirt product ID
            'qty'        => 1,
            'variations'    => [2, 5] // Blue, Medium
        ]);

        $this->assertEquals(7.00, $cartItem->getPrice($shirt));
        $this->assertEquals('shirt-blue-media', $cartItem->getSku($shirt));

    }

    /**
     * Test basic getters and setters
     */
    public function testGettersSetters() {
        $combo = new \CartLoad\Product\Combination();
        $price = new \CartLoad\Product\Price\Simple(5.00);

        $combo->setId(1);
        $combo->setPrice($price);
        $combo->setSku('p0001');
        $combo->setVariations([2, 5]);
        $this->assertEquals($combo->getId(), 1);
        $this->assertEquals($combo->getPrice(), $price);
        $this->assertEquals($combo->getSku(), 'p0001');
        $this->assertEquals($combo->getVariations(), [2, 5]);
    }
}