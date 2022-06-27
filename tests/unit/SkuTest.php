<?php

namespace CartLoad\Tests\unit;

use CartLoad\Cart\Item;
use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Product;
use Tests\Support\UnitTester;

class SkuTest extends \Codeception\Test\Unit
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
    public function testSkuReplacement()
    {
        $pen = Product::make([
            'id' => 1,
            'name' => 'Pen',
            'sku' => 'pen',
            'price' => 4.95,
            'variations' => [
                [
                    'id' => 1,
                    'name' => 'Type of Pen',
                    'required' => true,
                    'items' => [
                        ['id' => 1, 'name' => 'Ballpoint', 'sku' => 'ballpoint'],
                        ['id' => 2, 'name' => 'Fountain',
                            'price' => 10.95,
                            'price_effect' => PriceInterface::PRICE_REPLACE_ALL,
                            'sku' => ['sku' => 'fountain-black', 'effect' => SkuInterface::SKU_REPLACE_ALL]
                        ],
                        ['id' => 3, 'name' => 'Gel', 'price' => 4.95, 'sku' => 'gel'],
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Color of Pen',
                    'required' => true,
                    'items' => [
                        ['id' => 4, 'name' => 'Blue', 'sku' => 'blue'],
                        ['id' => 5, 'name' => 'Black', 'sku' => 'black'],
                    ]
                ],
            ],
        ]);

        //-- Fountain Pen
        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1, // Pen product ID
            'qty'        => 1
        ]);
        $cartItem->addVariation(2);
        $cartItem->addVariation(4);

        $this->assertEquals(1, $cartItem->getId());
        $this->assertEquals(1, $cartItem->getProductId());

        $this->assertEquals(10.95, $cartItem->getPrice($pen));
        //-- The SKU should replace the blue sku variant with fountain-black
        $this->assertEquals('pen-fountain-black', $cartItem->getSku($pen));

    }

    public function testEmptySku()
    {
        $pen = Product::make([
            'id' => 1,
            'name' => 'Pen',
            'sku' => '',
            'price' => 4.95,
            'variations' => [
                [
                    'id' => 1,
                    'name' => 'Type of Pen',
                    'required' => true,
                    'items' => [
                        ['id' => 1, 'name' => 'Ballpoint', 'sku' => 'ballpoint'],
                        ['id' => 2, 'name' => 'Fountain',
                         'price' => 10.95,
                         'price_effect' => PriceInterface::PRICE_REPLACE_ALL,
                         'sku' => ['sku' => 'fountain-black', 'effect' => SkuInterface::SKU_REPLACE_ALL, 'delimiter' => '=']
                        ],
                        ['id' => 3, 'name' => 'Gel', 'price' => 4.95, 'sku' => ['sku' => 'gel', 'effect' => SkuInterface::SKU_END_OF]],
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Color of Pen',
                    'required' => true,
                    'items' => [
                        ['id' => 4, 'name' => 'Blue', 'sku' => ['sku' => 'blue', 'effect' => SkuInterface::SKU_START_OF]],
                        ['id' => 5, 'name' => 'Black', 'sku' => 'black'],
                    ]
                ],
            ],
        ]);

        //-- Fountain Pen
        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1, // Pen product ID
            'qty'        => 1,
            'variations' => [3,4],
        ]);


        $this->assertEquals(4.95 + 4.95, $cartItem->getPrice($pen));
        $this->assertEquals('-blue-gel', $cartItem->getSku($pen));
    }

    public function testSkuStarts()
    {
        $pen = Product::make([
            'id' => 1,
            'name' => 'Pen',
            'sku' => 'pen',
            'price' => 4.95,
            'variations' => [
                [
                    'id' => 1,
                    'name' => 'Type of Pen',
                    'required' => true,
                    'items' => [
                        ['id' => 1, 'name' => 'Ballpoint', 'sku' => 'ballpoint'],
                        ['id' => 2, 'name' => 'Fountain',
                            'price' => 10.95,
                            'price_effect' => PriceInterface::PRICE_REPLACE_ALL,
                            'sku' => ['sku' => 'fountain-black', 'effect' => SkuInterface::SKU_REPLACE_ALL, 'delimiter' => '=']
                        ],
                        ['id' => 3, 'name' => 'Gel', 'price' => 4.95, 'sku' => ['sku' => 'gel', 'effect' => SkuInterface::SKU_END_OF]],
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Color of Pen',
                    'required' => true,
                    'items' => [
                        ['id' => 4, 'name' => 'Blue', 'sku' => ['sku' => 'blue', 'effect' => SkuInterface::SKU_START_OF]],
                        ['id' => 5, 'name' => 'Black', 'sku' => 'black'],
                    ]
                ],
            ],
        ]);

        //-- Fountain Pen
        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1, // Pen product ID
            'qty'        => 1,
            'variations' => [3,4],
        ]);


        $this->assertEquals(4.95 + 4.95, $cartItem->getPrice($pen));
        $this->assertEquals('pen-blue-gel', $cartItem->getSku($pen));

        //-- Fountain Pen - Black, blue is not an option
        $cartItem2 = Item::make([
            'id'         => 1,
            'product_id' => 1, // Pen product ID
            'qty'        => 1,
            'variations' => [2,4],
        ]);
        //-- The SKU should replace the blue sku variant with fountain-black
        $this->assertEquals('pen=fountain-black', $cartItem2->getSku($pen));
        $this->assertEquals(10.95, $cartItem2->getPrice($pen));
    }

    public function testSkuStartsSingleSku()
    {
        $pen = Product::make([
            'id' => 1,
            'name' => 'Pen',
            'price' => 4.95,
            'variations' => [
                [
                    'id' => 2,
                    'name' => 'Color of Pen',
                    'required' => true,
                    'items' => [
                        ['id' => 4, 'name' => 'Blue', 'sku' => ['sku' => 'blue', 'effect' => SkuInterface::SKU_START_OF]],
                        ['id' => 5, 'name' => 'Black', 'sku' => 'black'],
                    ]
                ],
            ],
        ]);

        //-- Fountain Pen
        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1, // Pen product ID
            'qty'        => 1,
            'variations' => [4],
        ]);


        //-- When there is no base SKU
        $this->assertEquals(4.95, $cartItem->getPrice($pen));
        $this->assertEquals('blue', $cartItem->getSku($pen));

        //-- When there is a base SKU
        $pen->setSku('pen');
        $this->assertEquals('pen-blue', $cartItem->getSku($pen));

        //-- When there is no start SKU but the effect is in place
        $pen->getVariations()[0]->getItems()[0]->setSku(null);
        $this->assertEquals('pen', $cartItem->getSku($pen));

        //-- Add second variation item of the same variation with a start effect
        $pen->getVariations()[0]->getItems()[0]->setSku('blue'); //reset SKU
        $pen->getVariations()[0]->getItems()[1]->setSkuEffect(SkuInterface::SKU_START_OF);
        $cartItem->addVariation(5);
        $this->assertEquals('pen-black-blue', $cartItem->getSku($pen));
    }

}
