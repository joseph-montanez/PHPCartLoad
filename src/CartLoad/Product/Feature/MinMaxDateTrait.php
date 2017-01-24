<?php namespace CartLoad\Product\Feature;


trait MinMaxDateTrait
{
    /** @var \DateTime $min_qty */
    protected $min_date;

    /** @var \DateTime $max_qty */
    protected $max_date;

    /** @var boolean $no_minimum_qty_limit */
    protected $no_minimum_date_limit;

    /** @var boolean $no_maximum_qty_limit */
    protected $no_maximum_date_limit;

    /**
     * @param \DateTime $now
     * @return bool
     */
    public function inMinMaxDateRange(\DateTime $now)
    {
        $no_min_limit = $this->isNoMinimumDateLimit();
        $no_max_limit = $this->isNoMaximumDateLimit();
        $min_date = $this->getMinDate();
        $max_date = $this->getMaxDate();

        if ($no_min_limit && !$no_max_limit && $now <= $max_date) {
            return true;
        } else {
            if (!$no_min_limit && $no_max_limit && $now >= $min_date) {
                return true;
            } else {
                if (!$no_min_limit && !$no_max_limit && $now >= $min_date && $now <= $max_date) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * @return boolean
     */
    public function isNoMinimumDateLimit()
    {
        return $this->no_minimum_date_limit;
    }

    /**
     * @param boolean $no_minimum_date_limit
     * @return self
     */
    public function setNoMinimumDateLimit($no_minimum_date_limit)
    {
        $this->no_minimum_date_limit = $no_minimum_date_limit;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isNoMaximumDateLimit()
    {
        return $this->no_maximum_date_limit;
    }

    /**
     * @param boolean $no_maximum_date_limit
     * @return self
     */
    public function setNoMaximumDateLimit($no_maximum_date_limit)
    {
        $this->no_maximum_date_limit = $no_maximum_date_limit;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMinDate()
    {
        return $this->min_date;
    }

    /**
     * @param \DateTime $min_date
     * @return self
     */
    public function setMinDate($min_date)
    {
        $this->min_date = $min_date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMaxDate()
    {
        return $this->max_date;
    }

    /**
     * @param \DateTime $max_date
     * @return self
     */
    public function setMaxDate(\DateTime $max_date)
    {
        $this->max_date = $max_date;

        return $this;
    }
}