<?php

namespace CartLoad\Tests\unit;

use Tests\Support\UnitTester;

class PriceTest extends \Codeception\Test\Unit
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
    public function testSimplePrice()
    {
        $simplePrice = new \CartLoad\Product\Price\Simple();
        $simplePrice->setPrice(3.00);
        $this->assertEquals(3.00, $simplePrice->getPrice());
    }
}