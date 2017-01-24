<?php


use CartLoad\Product\Product;
use CartLoad\Product\ProductFactory;

class VariationsTest extends \Codeception\Test\Unit
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
        $cartItem = new \CartLoad\Cart\Item([
            'id'         => 1,
            'product_id' => 1, //Shirt product ID
            'qty'        => 1,
            'variations' => [2, 5] // Blue, Medium
        ]);
    
        $this->assertEquals(4.95 + 0.4 + 1.1, $shirt->getCartPrice($cartItem));
        $this->assertEquals('shirt-b-m', $shirt->getCartSku($cartItem));

        $shirt->getVariations()[0]->setSkuDelimiter('_');
        $this->assertEquals('shirt_b-m', $shirt->getCartSku($cartItem));

        $blue_variation = $shirt->getVariations()[0]->getItems()[1];
        $blue_variation->setSkuDelimiter('/');
        $this->assertEquals('shirt/b-m', $shirt->getCartSku($cartItem));
    }
}