<?php

use CartLoad\Cart\Item;
use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Product;

class SkuTest extends \Codeception\Test\Unit
{

    /**
     * @var \UnitTester
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
            'name' => 'Black Pen',
            'sku' => 'pen-black',
            'price' => 4.95,
            'variations' => [
                [
                    'id' => 1,
                    'name' => 'Type of Pen',
                    'required' => true,
                    'items' => [
                        ['id' => 1, 'name' => 'Ballpoint', 'sku' => ['sku' => 'pen-ballpoint-black', 'effect' => SkuInterface::SKU_REPLACE_ALL]],
                        ['id' => 2, 'name' => 'Fountain',
                            'price' => 10.95,
                            'price_effect' => PriceInterface::PRICE_REPLACE_ALL,
                            'sku' => ['sku' => 'pen-fountain-black', 'effect' => SkuInterface::SKU_REPLACE_ALL]
                        ],
                        ['id' => 3, 'name' => 'Gel', 'price' => 4.95, 'sku' => ['sku' => 'pen-gel-black', 'effect' => SkuInterface::SKU_REPLACE_ALL]],
                    ]
                ],
            ],
        ]);

        //-- Blue Medium Shirt
        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1, // Pen product ID
            'qty'        => 1
        ]);
        $cartItem->addVariation(2);

        $this->assertEquals(1, $cartItem->getId());

        $this->assertEquals(10.95, $cartItem->getPrice($pen));
        //-- TODO: This is failing the replacement feature is not working for SKUs...
        $this->assertEquals('pen-fountain-black', $cartItem->getSku($pen));

    }

}