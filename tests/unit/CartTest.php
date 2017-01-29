<?php
use CartLoad\Cart\Item;
use CartLoad\Product\Product;


class CartTest extends \Codeception\Test\Unit
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

    public function testNullCartPrice()
    {
        $product = Product::make([]);
        $this->assertEquals(null, $product->getPrice());

        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1,
            'qty'        => 1
        ]);

        $this->assertEquals(null, $cartItem->getPrice($product));
    }

    public function testSimpleCartPrice()
    {
        $product = Product::make([
            'price' => 3.00
        ]);

        $cartItem = Item::make([
            'id'         => 1,
            'product_id' => 1,
            'qty'        => 1
        ]);

        $this->assertEquals(3.00, $cartItem->getPrice($product));
    }

}