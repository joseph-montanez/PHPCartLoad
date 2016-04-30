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
                ['Bulk' => ['min_qty' => 1, 'max_qty' => 9, 'price' => 19.95]],
                ['Bulk' => ['min_qty' => 10, 'price' => 14.95]],
            ]
        ]);

        $qty = 10;


        //-- This will return the simple price: 14.95
        $this->assertEquals(14.95, $apple->getPrice($qty));

    }

    public function testBulkPricingAlt()
    {
        $apple = new Item([
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
               ['min_qty' => 1, 'max_qty' => 9, 'price' => 19.95],
               ['min_qty' => 10, 'price' => 14.95],
            ]
        ]);

        $qty = 10;


        //-- This will return the simple price: 14.95
        $this->assertEquals(14.95, $apple->getPrice($qty));

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

        $this->assertEquals(14.95, $apple->getPrice($qty));

    }

    /**
     * Test to see if a date range was added that the new price works, this could be for a holiday special
     */
    public function testBulkPricingDateRange()
    {
        $apple = new Item([
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                ['min_qty' => 1, 'max_qty' => 9, 'price' => 14.95],
                ['min_qty' => 10, 'max_qty' => 19, 'price' => 13.95],
                ['min_date' => '2016-01-01', 'max_date' => '2016-01-10', 'min_qty' => 10, 'max_qty' => 19, 'price' => 11.95],
            ]
        ]);

        $this->assertEquals(14.95, $apple->getPrice(2));
        $this->assertEquals(13.95, $apple->getPrice(11));
        $this->assertEquals(11.95, $apple->getPrice(11, new \DateTime('2016-01-05')));

    }

    public function testCustomBulkPrice()
    {
        $apple = new Item([
            'name' => 'Apple',
            'sku' => 'a',
            'price' => [
                ['CustomBulk' => ['min_qty' => 1, 'max_qty' => 9, 'price' => 4.95]],
                ['CustomBulk' => ['min_qty' => 10, 'max_qty' => 19, 'price' => 3.95]],
            ]
        ]);

        $this->assertEquals(14.95, $apple->getPrice(6));
        $this->assertEquals(13.95, $apple->getPrice(11));
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
    public function getPrice() {
        return $this->basePrice + $this->price;
    }
}