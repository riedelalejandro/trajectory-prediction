<?php
namespace TrajectoryPrediction;

use Carbon\Carbon;

/**
 * Class DistanceHistory.
 */
class DistanceHistory
{
    /**
     * @var Carbon
     */
    private $date;
    /**
     * @var int
     */
    private $distance;

    /**
     * DistanceLog constructor.
     *
     * @param Carbon $date
     * @param int    $distance
     */
    public function __construct(Carbon $date, $distance)
    {
        $this->date = $date;
        $this->distance = $distance;
    }

    /**
     * @return Carbon
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }
}
