<?php
/**
 * Created by PhpStorm.
 * User: josephmontanez
 * Date: 5/14/18
 * Time: 11:12 PM
 */

namespace CartLoad\Product;

use CartLoad\Product\Price\Simple;

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
