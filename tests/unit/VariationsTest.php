<?php

namespace CartLoad\Tests\unit;

use CartLoad\Cart\Item;
use CartLoad\Product\Feature\PriceInterface;
use CartLoad\Product\Feature\SkuInterface;
use CartLoad\Product\Product;
use Tests\Support\UnitTester;

class VariationsTest extends \Codeception\Test\Unit
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
    public function testMe()
    {
        $shirt = Product::make([
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
            ]
        ]);

        //-- Blue Medium Shirt
        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1, //Shirt product ID
            'qty'        => 1,
            'variations' => [2, 5] // Blue, Medium
        ]);
    
        $this->assertEquals(4.95 + 0.4 + 1.1, $cartItem->getPrice($shirt));
        $this->assertEquals('shirt-b-m', $cartItem->getSku($shirt));

        $shirt->getVariations()[0]->setSkuDelimiter('_');
        $this->assertEquals('shirt_b-m', $cartItem->getSku($shirt));

        $blue_variation = $shirt->getVariations()[0]->getItems()[1];
        $blue_variation->setSkuDelimiter('/');
        $this->assertEquals('shirt/b-m', $cartItem->getSku($shirt));
    }

    public function testGetterSetterFactory()
    {


        $variation = new \CartLoad\Product\Variation\Variation();
        $variation->fromArray([
            'id' => 1,
            'name' => 'Red',
            'required' => false,
            'order' => 1,
            'price' => 1.00,
            'price_effect' => PriceInterface::PRICE_REPLACE_ALL,
            'sku' => [
                'sku' => 'apple-red',
                'delimiter' => '|',
                'effect' => SkuInterface::SKU_REPLACE_ALL
            ],
        ]);


        $this->assertEquals(1, $variation->getId());
        $this->assertEquals('Red', $variation->getName());
        $this->assertEquals(false, $variation->getRequired());
        $this->assertEquals(1, $variation->getOrder());
        $this->assertEquals(1.00, $variation->getPrice());
        $this->assertEquals(PriceInterface::PRICE_REPLACE_ALL, $variation->getPriceEffect());
        $this->assertEquals(SkuInterface::SKU_REPLACE_ALL, $variation->getSkuEffect());
    }
}