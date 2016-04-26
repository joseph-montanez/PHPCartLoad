<?php


class MinMaxQtyTest extends \Codeception\Test\Unit {
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before() {
    }

    protected function _after() {
    }

    // tests
    public function testRange() {
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setMinQty(1);
        $bulkPrice->setMaxQty(10);
        $bulkPrice->setNoMaximumQtyLimit(TRUE);
        $bulkPrice->setNoMinimumQtyLimit(TRUE);

        $this->assertEquals(1, $bulkPrice->getMinQty());
        $this->assertEquals(10, $bulkPrice->getMaxQty());
        $this->assertEquals(TRUE, $bulkPrice->isNoMinimumQtyLimit());
        $this->assertEquals(TRUE, $bulkPrice->isNoMaximumQtyLimit());
    }

    public function testNoMinMaxQty() {
        $qty = 3;
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setNoMaximumQtyLimit(TRUE);
        $bulkPrice->setNoMinimumQtyLimit(TRUE);

        $this->assertEquals(TRUE, $bulkPrice->inMinMaxQtyRange($qty));
    }

    public function testNoMaxQty() {
        $min_qty = 1;
        $qty = 3;

        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setNoMaximumQtyLimit(TRUE);
        $bulkPrice->setMinQty($min_qty);

        //-- Qty is within range
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxQtyRange($qty));

        //-- Min qty is larger than qty so its not within range
        $bulkPrice->setMinQty(5);
        $this->assertEquals(FALSE, $bulkPrice->inMinMaxQtyRange($qty));

        //-- Now is back within range
        $qty = 6;
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxQtyRange($qty));
    }

    public function testNoMinQty() {
        $max_qty = 10;
        $qty = 10;
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setNoMinimumQtyLimit(TRUE);
        $bulkPrice->setMaxQty($max_qty);

        //-- Qty is within range
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxQtyRange($qty));

        //-- Max qty is before now so its not within range
        $bulkPrice->setMaxQty(5);
        $this->assertEquals(FALSE, $bulkPrice->inMinMaxQtyRange($qty));

        //-- Qty is back within range
        $qty = 4;
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxQtyRange($qty));
    }

    public function testBetweenQty() {
        $min_qty = 1;
        $max_qty = 10;
        $qty = 5;
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setMinQty($min_qty);
        $bulkPrice->setMaxQty($max_qty);

        //-- Qty is within range
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxQtyRange($qty));

        //-- Max qty is before now so its not within range
        $bulkPrice->setMaxQty(4);
        $this->assertEquals(FALSE, $bulkPrice->inMinMaxQtyRange($qty));

        //-- Qty is back within range
        $qty = 4;
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxQtyRange($qty));
    }
}