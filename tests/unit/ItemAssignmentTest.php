<?php


class ItemAssignmentTest extends \Codeception\Test\Unit
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
    public function testFromArray()
    {
        $sku = 'apl';
        $name = 'Apple';
        $id = 12;
        $desc = 'Shiny Red Apple';
        $price = 1.00;

        $data = [
            'id' => $id,
            'sku' => $sku,
            'name' => $name,
            'description' => $desc,
            'price' => $price
        ];

        $item = new \CartLoad\Product\Item();
        $item->fromArray($data);

        $this->assertEquals(12, $item->getId());
        $this->assertEquals($sku, $item->getSku());
        $this->assertEquals($name, $item->getName());
        $this->assertEquals($desc, $item->getDescription());
        $this->assertEquals($price, $item->getPrice(1));

        $item->setName('Banana');
        $this->assertEquals('Banana', $item->getName());

        $item->setSku('b');
        $this->assertEquals('b', $item->getSku());
    }
}