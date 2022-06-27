<?php

namespace CartLoad\Tests\unit;

class ProductTest extends \Codeception\Test\Unit
{
    public function testGetterSetters()
    {
        $product = new \CartLoad\Product\Product();

        $variationSet = new \CartLoad\Product\Variation\VariationSet();
        $variationSet->setId(2);
        $variationSet->setName('Size');
        $variationSet->setRequired(false);
        $variationSet->setItems([
            new \CartLoad\Product\Variation\Variation([
                'id'       => 4,
                'name'     => 'Small',
                'price'    => 1.0,
                'sku'      => 's',
                'required' => false,
                'order'    => 1,
            ]),
            new \CartLoad\Product\Variation\Variation([
                'id'       => 5,
                'name'     => 'Medium',
                'price'    => 1.1,
                'sku'      => 'm',
                'required' => false,
                'order'    => 1,
            ]),
            new \CartLoad\Product\Variation\Variation([
                'id'       => 6,
                'name'     => 'Large',
                'price'    => 1.2,
                'sku'      => 'l',
                'required' => false,
                'order'    => 1,
            ]),
        ]);

        $product->setId(1);
        $product->setSku('a0001');
        $product->setName('Apple');
        $product->setDescription('Red and delicious');
        $product->setWeight(1.0);
        $product->setVariations([$variationSet]);

        $this->assertEquals($product->getId(), 1);
        $this->assertEquals($product->getWeight(), 1.0);
        $this->assertEquals($product->getSku(), 'a0001');
        $this->assertEquals($product->getName(), 'Apple');
        $this->assertEquals($product->getDescription(), 'Red and delicious');
        $this->assertEquals($product->getVariations(), [$variationSet]);
    }

}