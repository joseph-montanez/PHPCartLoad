<?php namespace CartLoad\Product\Feature;


interface MinMaxDateInterface
{
    /**
     * @return \DateTime
     */
    public function getMinDate();

    /**
     * @param \DateTime $min_date
     * @return self
     */
    public function setMinDate($min_date);

    /**
     * @return \DateTime
     */
    public function getMaxDate();

    /**
     * @param \DateTime $max_date
     * @return self
     */
    public function setMaxDate(\DateTime $max_date);

    /**
     * @return boolean
     */
    public function isNoMinimumDateLimit();

    /**
     * @param boolean $no_minimum_date_limit
     * @return self
     */
    public function setNoMinimumDateLimit($no_minimum_date_limit);

    /**
     * @return boolean
     */
    public function isNoMaximumDateLimit();

    /**
     * @param boolean $no_maximum_date_limit
     * @return self
     */
    public function setNoMaximumDateLimit($no_maximum_date_limit);

    /**
     * @param \DateTime $now
     * @return bool
     */
    public function inMinMaxDateRange(\DateTime $now);
}