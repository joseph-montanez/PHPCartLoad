<?php

namespace CartLoad\Tests\unit;

use CartLoad\Product\Price\Simple;
use CartLoad\Product\PriceTableFactory;

class PriceTableFactoryTest extends \Codeception\Test\Unit
{

    public function testMakeFloats()
    {
        $priceFactory = new PriceTableFactory();
        $priceTable = $priceFactory->make([
            10.00
        ]);

        $prices = $priceTable->getPrices(1);

        $this->assertEquals($prices[0]->getPrice(), 10.00);
    }

    public function testMakeSimple()
    {
        $priceFactory = new PriceTableFactory();
        $priceTable = $priceFactory->make([
            ['Simple' => 10.00]
        ]);

        $prices = $priceTable->getPrices(1);

        $this->assertEquals($prices[0]->getPrice(), 10.00);
        $this->assertEquals($prices[0] instanceof Simple, true);
    }
}
