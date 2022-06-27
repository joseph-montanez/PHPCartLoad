<?php

namespace CartLoad\Tests\unit;

use CartLoad\Cart\Item;
use CartLoad\Product\Product;
use Tests\Support\UnitTester;


class BulkItemTest extends \Codeception\Test\Unit
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
    public function testBulkPricing()
    {
        $apple = Product::make([
            'id' => 1,
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                ['Bulk' => ['min_qty' => 1, 'max_qty' => 9, 'price' => 19.95]],
                ['Bulk' => ['min_qty' => 10, 'price' => 14.95]],
            ]
        ]);

        $qty = 10;

        $cart_item = new Item();
        $cart_item->setId(1);
        $cart_item->setProductId(1);
        $cart_item->setQty($qty);

        //-- This will return the simple price: 14.95
        $this->assertEquals(14.95, $cart_item->getPrice($apple));

    }

    public function testBulkPricingAlt()
    {
        $apple = Product::make([
            'id' => 1,
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
               ['min_qty' => 1, 'max_qty' => 9, 'price' => 19.95],
               ['min_qty' => 10, 'price' => 14.95],
            ]
        ]);

        $qty = 10;

        $cart_item = new Item();
        $cart_item->setId(1);
        $cart_item->setProductId(1);
        $cart_item->setQty($qty);

        //-- This will return the simple price: 14.95
        $this->assertEquals(14.95, $cart_item->getPrice($apple));

    }

    public function testBulkPricingUndefinedBehavior()
    {
        $apple = Product::make([
            'id' => 1,
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                ['min_qty' => 1, 'price' => 14.95],
                ['max_qty' => 10, 'price' => 13.95],
            ]
        ]);

        $qty = 10;

        $cart_item = new Item();
        $cart_item->setId(1);
        $cart_item->setProductId(1);
        $cart_item->setQty($qty);

        $this->assertEquals(14.95, $cart_item->getPrice($apple));

    }

    /**
     * Test to see if a date range was added that the new price works, this could be for a holiday special
     */
    public function testBulkPricingDateRange()
    {
        $apple = Product::make([
            'id' => 1,
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                ['min_qty' => 1, 'max_qty' => 9, 'price' => 14.95],
                ['min_qty' => 10, 'max_qty' => 19, 'price' => 13.95],
                ['min_date' => '2016-01-01', 'max_date' => '2016-01-10', 'min_qty' => 10, 'max_qty' => 19, 'price' => 11.95],
            ]
        ]);

        $cart_item = new Item();
        $cart_item->setId(1);
        $cart_item->setProductId(1);


        $cart_item->setQty(2);
        $this->assertEquals(14.95, $cart_item->getPrice($apple));

        $cart_item->setQty(11);
        $this->assertEquals(13.95, $cart_item->getPrice($apple));

        $cart_item->setQty(11);
        $this->assertEquals(11.95, $cart_item->getPrice($apple, new \DateTime('2016-01-05')));

    }

    public function testCustomBulkPrice()
    {
        $apple = Product::make([
            'id' => 1,
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                [CustomBulk::class => ['min_qty' => 1, 'max_qty' => 9, 'price' => 4.95]],
                [CustomBulk::class => ['min_qty' => 10, 'max_qty' => 19, 'price' => 3.95]],
            ]
        ]);

        $cart_item = new Item();
        $cart_item->setId(1);
        $cart_item->setProductId(1);

        $cart_item->setQty(6);
        $this->assertEquals(14.95, $cart_item->getPrice($apple));

        $cart_item->setQty(11);
        $this->assertEquals(13.95, $cart_item->getPrice($apple));
    }
}

class CustomBulk extends \CartLoad\Product\Price\Bulk {
    /**
     * @var float
     */
    protected $basePrice = 10.00;

    /**
     * @return float
     */
    public function getPrice(): float {
        return $this->basePrice + $this->price;
    }
    
    public function make(array $data) {
        return (new \CartLoad\Product\Price\BulkFactory)->make($data, new self);
    }
}