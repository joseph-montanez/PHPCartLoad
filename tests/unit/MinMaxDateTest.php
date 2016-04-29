<?php


class MinMaxDateTest extends \Codeception\Test\Unit {
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before() {
    }

    protected function _after() {
    }

    // tests
    public function testMe() {
        $min_date = new DateTime('2015-10-10');
        $max_date = new DateTime('2015-10-11');
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setMinDate($min_date);
        $bulkPrice->setMaxDate($max_date);
        $bulkPrice->setNoMinimumDateLimit(TRUE);
        $bulkPrice->setNoMaximumDateLimit(TRUE);

        $this->assertEquals($min_date, $bulkPrice->getMinDate());
        $this->assertEquals($max_date, $bulkPrice->getMaxDate());
        $this->assertEquals(TRUE, $bulkPrice->isNoMinimumDateLimit());
        $this->assertEquals(TRUE, $bulkPrice->isNoMaximumDateLimit());

    }

    public function testNoMinMaxDate() {
        $now_date = new DateTime('2015-10-10');
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setNoMaximumDateLimit(TRUE);
        $bulkPrice->setNoMinimumDateLimit(TRUE);

        //-- TODO: this should throw an exception, you cannot have no min and no max
        $this->assertEquals(FALSE, $bulkPrice->inMinMaxDateRange($now_date));
    }

    public function testNoMaxDate() {
        $min_date = new DateTime('2015-10-10');
        $now_date = new DateTime('2015-10-10');
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setNoMaximumDateLimit(TRUE);
        $bulkPrice->setMinDate($min_date);

        //-- Now is within range
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxDateRange($now_date));

        //-- Min date is after now so its not within range
        $min_date->setDate(2015, 10, 11);
        $this->assertEquals(FALSE, $bulkPrice->inMinMaxDateRange($now_date));

        //-- Now is back within range
        $now_date->setDate(2015, 10, 12);
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxDateRange($now_date));
    }

    public function testNoMinDate() {
        $max_date = new DateTime('2015-10-10');
        $now_date = new DateTime('2015-10-10');
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setNoMinimumDateLimit(TRUE);
        $bulkPrice->setMaxDate($max_date);

        //-- Now is within range
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxDateRange($now_date));

        //-- Max date is before now so its not within range
        $max_date->setDate(2015, 10, 9);
        $this->assertEquals(FALSE, $bulkPrice->inMinMaxDateRange($now_date));

        //-- Now is back within range
        $now_date->setDate(2015, 10, 9);
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxDateRange($now_date));
    }

    public function testBetweenDate() {
        $min_date = new DateTime('2015-10-10');
        $max_date = new DateTime('2015-10-20');
        $now_date = new DateTime('2015-10-15');
        $bulkPrice = new CartLoad\Product\Price\Bulk();
        $bulkPrice->setMinDate($min_date);
        $bulkPrice->setMaxDate($max_date);

        //-- Now is within range
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxDateRange($now_date));

        //-- Max date is before now so its not within range
        $min_date->setDate(2015, 10, 16);
        $this->assertEquals(FALSE, $bulkPrice->inMinMaxDateRange($now_date));

        //-- Now is back within range
        $now_date->setDate(2015, 10, 16);
        $this->assertEquals(TRUE, $bulkPrice->inMinMaxDateRange($now_date));
    }
}