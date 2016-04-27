<?php


use CartLoad\Product\Item;

class BulkItemTest extends \Codeception\Test\Unit
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
    public function testBulkPricing()
    {
        $apple = new Item([
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                19.95,
                ['Bulk' => ['min_qty' => 10, 'price' => 14.95]],
            ]
        ]);

        $qty = 10;


        //-- This will return the simple price: 14.95
        $this->assertEquals(14.95, $apple->getPrice($qty)->getPrice());

    }

    public function testBulkPricingAlt()
    {
        $apple = new Item([
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                19.95,
                ['min_qty' => 10, 'price' => 14.95],
            ]
        ]);

        $qty = 10;


        //-- This will return the simple price: 14.95
        $this->assertEquals(14.95, $apple->getPrice($qty)->getPrice());

    }

    public function testBulkPricingUndefinedBehavior()
    {
        $apple = new Item([
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                ['min_qty' => 1, 'price' => 14.95],
                ['max_qty' => 10, 'price' => 13.95],
            ]
        ]);

        $qty = 10;

        $this->assertEquals(14.95, $apple->getPrice($qty)->getPrice());

    }
}