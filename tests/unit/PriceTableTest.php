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
        $factory = new \CartLoad\Product\Price\BulkFactory();
        $bulk = new \CartLoad\Product\Price\Bulk();
        $factory->minMaxQtyFromArray($bulk, [
            'min_qty' => 1,
            'max_qty' => 10,
        ]);

        $this->assertEquals(1, $bulk->getMinQty());
        $this->assertEquals(10, $bulk->getMaxQty());

    }

    public function testMinUnlimitedQty() {
        $factory = new \CartLoad\Product\Price\BulkFactory();
        $bulk = new \CartLoad\Product\Price\Bulk();

        //-- All these values should have the same result
        $test_arrays = [
            [ 'min_qty' => 1 ],
            [ 'max_qty' => NULL, 'min_qty' => 1 ],
            [ 'max_qty' => -1, 'min_qty' => 1 ],
            [ 'max_qty' => FALSE, 'min_qty' => 1 ],
        ];

        foreach ($test_arrays as $test) {
            $factory->minMaxQtyFromArray($bulk, $test);

            $this->assertEquals(1, $bulk->getMinQty());
            $this->assertEquals(TRUE, $bulk->isNoMaximumQtyLimit());
        }
    }

    public function testMaxUnlimitedQty() {
        $factory = new \CartLoad\Product\Price\BulkFactory();
        $bulk = new \CartLoad\Product\Price\Bulk();

        //-- All these values should have the same result
        $test_arrays = [
            [ 'max_qty' => 10 ],
            [ 'min_qty' => NULL, 'max_qty' => 10 ],
            [ 'min_qty' => -1, 'max_qty' => 10 ],
            [ 'min_qty' => FALSE, 'max_qty' => 10 ],
        ];

        foreach ($test_arrays as $test) {
            $factory->minMaxQtyFromArray($bulk, $test);

            $this->assertEquals(10, $bulk->getMaxQty());
            $this->assertEquals(TRUE, $bulk->isNoMinimumQtyLimit());
        }
    }

    public function testUnlimitedQty() {
        $factory = new \CartLoad\Product\Price\BulkFactory();
        $bulk = new \CartLoad\Product\Price\Bulk();
        $factory->minMaxQtyFromArray($bulk, []);

        $this->assertEquals(TRUE, $bulk->isNoMinimumQtyLimit());
        $this->assertEquals(TRUE, $bulk->isNoMaximumQtyLimit());
    }

    // tests
    public function testMinMaxDate() {
        $factory = new \CartLoad\Product\Price\BulkFactory();

        $min_date = new \DateTime('2016-10-10');
        $max_date = new \DateTime('2016-11-10');
        $bulk = new \CartLoad\Product\Price\Bulk();
        $factory->minMaxDateFromArray($bulk, [
            'min_date' => '2016-10-10',
            'max_date' => '2016-11-10',
        ]);

        $this->assertEquals($min_date, $bulk->getMinDate());
        $this->assertEquals($max_date, $bulk->getMaxDate());

    }

    public function testMinUnlimitedDate() {
        $factory = new \CartLoad\Product\Price\BulkFactory();

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
            $factory->minMaxDateFromArray($bulk, $test);
            $this->assertEquals($min_date, $bulk->getMinDate());
            $this->assertEquals(TRUE, $bulk->isNoMaximumDateLimit());
        }

    }

    public function testMaxUnlimitedDate() {
        $factory = new \CartLoad\Product\Price\BulkFactory();

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
            $factory->minMaxDateFromArray($bulk, $test);
            $this->assertEquals($max_date, $bulk->getMaxDate());
            $this->assertEquals(TRUE, $bulk->isNoMinimumDateLimit());
        }
    }

    public function testUnlimitedQDate() {
        $factory = new \CartLoad\Product\Price\BulkFactory();

        $bulk = new \CartLoad\Product\Price\Bulk();
        $factory->minMaxDateFromArray($bulk, []);

        $this->assertEquals(TRUE, $bulk->isNoMinimumDateLimit());
        $this->assertEquals(TRUE, $bulk->isNoMaximumDateLimit());
    }
}