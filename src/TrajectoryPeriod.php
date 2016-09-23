<?php

namespace TrajectoryPrediction;

use Carbon\Carbon;

/**
 * Class TrajectoryPeriod.
 */
class TrajectoryPeriod
{
    /**
     * @var int
     */
    private $year;
    /**
     * @var int
     */
    private $month;
    /**
     * @var int
     */
    private $distance;

    /**
     * TrajectoryPeriod constructor.
     *
     * @param int $year
     * @param int $month
     * @param int $distance
     */
    public function __construct($year, $month, $distance)
    {
        $this->year = $year;
        $this->month = $month;
        $this->distance = $distance;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param $distance
     *
     * @return int
     */
    public function increment($distance)
    {
        return $this->distance += $distance;
    }

    /**
     * @return float
     */
    public function getDailyDistance()
    {
        return round($this->distance / Carbon::create($this->year, $this->month)->daysInMonth);
    }
}
