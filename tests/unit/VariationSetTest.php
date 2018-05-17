<?php

use CartLoad\Product\Variation\VariationSet;

class VariationSetTest extends \Codeception\Test\Unit
{

    public function testFromArray()
    {
        $set = new VariationSet();
        $set->fromArray([
            'id' => 1,
            'name' => 'Size',
            'required' => true,
            'order' => 10,
            'items' => [],
        ]);

        $this->assertEquals(1, $set->getId());
        $this->assertEquals('Size', $set->getName());
        $this->assertEquals(true, $set->getRequired());
        $this->assertEquals(10, $set->getOrder());
        $this->assertEquals([], $set->getItems());

        $this->assertEquals([], $set->calculatePrice(1));
        $this->assertEquals([], $set->calculateSkus(1));

        $set->setItems([
            new CartLoad\Product\Variation\Variation(['id' => 1, 'name' => 'Small', 'price' => 0.5, 'sku' => 's']),
            new CartLoad\Product\Variation\Variation(['id' => 2, 'name' => 'Medium', 'price' => 0.7, 'sku' => 'm']),
            new CartLoad\Product\Variation\Variation(['id' => 3, 'name' => 'Large', 'price' => 1.0, 'sku' => 'l']),
        ]);


        $this->assertEquals(false, $set->hasVariationIds([4]));
        $this->assertEquals(true, $set->hasVariationIds([3]));
    }
}