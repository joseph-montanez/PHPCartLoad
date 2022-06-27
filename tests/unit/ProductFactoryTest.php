<?php

namespace CartLoad\Tests\unit;

class ProductFactoryTest extends \Codeception\Test\Unit
{
    public function testWeight() {
        $factory = new \CartLoad\Product\ProductFactory();
        $product = $factory->make([
            'weight' => 1.0
        ]);

        $this->assertEquals($product->getWeight(), 1.0);
    }

}