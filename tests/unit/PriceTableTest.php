<?php


class PriceTableTest extends \Codeception\Test\Unit {
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before() {
    }

    protected function _after() {
    }

    // tests
    public function testMinMaxQty() {
        $bulk = new \CartLoad\Product\Price\Bulk();
        $bulk->minMaxQtyFromArray([
            'min_qty' => 1,
            'max_qty' => 10,
        ]);

        $this->assertEquals(1, $bulk->getMinQty());
        $this->assertEquals(10, $bulk->getMaxQty());

    }

    public function testMinUnlimitedQty() {
        $bulk = new \CartLoad\Product\Price\Bulk();

        //-- All these values should have the same result
        $test_arrays = [
            [ 'min_qty' => 1 ],
            [ 'max_qty' => NULL, 'min_qty' => 1 ],
            [ 'max_qty' => -1, 'min_qty' => 1 ],
            [ 'max_qty' => FALSE, 'min_qty' => 1 ],
        ];

        foreach ($test_arrays as $test) {
            $bulk->minMaxQtyFromArray($test);
            $this->assertEquals(1, $bulk->getMinQty());
            $this->assertEquals(TRUE, $bulk->isNoMaximumQtyLimit());
        }
    }

    public function testMaxUnlimitedQty() {
        $bulk = new \CartLoad\Product\Price\Bulk();

        //-- All these values should have the same result
        $test_arrays = [
            [ 'max_qty' => 10 ],
            [ 'min_qty' => NULL, 'max_qty' => 10 ],
            [ 'min_qty' => -1, 'max_qty' => 10 ],
            [ 'min_qty' => FALSE, 'max_qty' => 10 ],
        ];

        foreach ($test_arrays as $test) {
            $bulk->minMaxQtyFromArray($test);
            $this->assertEquals(10, $bulk->getMaxQty());
            $this->assertEquals(TRUE, $bulk->isNoMinimumQtyLimit());
        }
    }

    public function testUnlimitedQty() {
        $bulk = new \CartLoad\Product\Price\Bulk();
        $bulk->minMaxQtyFromArray([]);

        $this->assertEquals(TRUE, $bulk->isNoMinimumQtyLimit());
        $this->assertEquals(TRUE, $bulk->isNoMaximumQtyLimit());
    }

    // tests
    public function testMinMaxDate() {
        $min_date = new \DateTime('2016-10-10');
        $max_date = new \DateTime('2016-11-10');
        $bulk = new \CartLoad\Product\Price\Bulk();
        $bulk->minMaxDateFromArray([
            'min_date' => '2016-10-10',
            'max_date' => '2016-11-10',
        ]);

        $this->assertEquals($min_date, $bulk->getMinDate());
        $this->assertEquals($max_date, $bulk->getMaxDate());

    }

    public function testMinUnlimitedDate() {
        $min_date = new \DateTime('2016-10-10');
        $bulk = new \CartLoad\Product\Price\Bulk();

        //-- All these values should have the same result
        $test_arrays = [
            [ 'min_date' => '2016-10-10' ],
            [ 'max_date' => NULL, 'min_date' => '2016-10-10' ],
            [ 'max_date' => -1, 'min_date' => '2016-10-10' ],
            [ 'max_date' => FALSE, 'min_date' => '2016-10-10' ],
        ];

        foreach ($test_arrays as $test) {
            $bulk->minMaxDateFromArray($test);
            $this->assertEquals($min_date, $bulk->getMinDate());
            $this->assertEquals(TRUE, $bulk->isNoMaximumDateLimit());
        }

    }

    public function testMaxUnlimitedDate() {
        $max_date = new \DateTime('2016-11-10');
        $bulk = new \CartLoad\Product\Price\Bulk();

        //-- All these values should have the same result
        $test_arrays = [
            [ 'max_date' => '2016-11-10' ],
            [ 'min_date' => NULL, 'max_date' => '2016-11-10' ],
            [ 'min_date' => -1, 'max_date' => '2016-11-10' ],
            [ 'min_date' => FALSE, 'max_date' => '2016-11-10' ],
        ];

        foreach ($test_arrays as $test) {
            $bulk->minMaxDateFromArray($test);
            $this->assertEquals($max_date, $bulk->getMaxDate());
            $this->assertEquals(TRUE, $bulk->isNoMinimumDateLimit());
        }
    }

    public function testUnlimitedQDate() {
        $bulk = new \CartLoad\Product\Price\Bulk();
        $bulk->minMaxDateFromArray([]);

        $this->assertEquals(TRUE, $bulk->isNoMinimumDateLimit());
        $this->assertEquals(TRUE, $bulk->isNoMaximumDateLimit());
    }
}