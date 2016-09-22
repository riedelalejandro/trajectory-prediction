<?php

namespace TrajectoryPrediction;

use Carbon\Carbon;

/**
 * Interface DistanceLog
 * @package TrajectoryPrediction
 */
class DistanceLog
{
    /**
     * @var Carbon
     */
    private $createdAt;
    private $distance;

    /**
     * DistanceLog constructor.
     * @param $createdAt
     * @param $distance
     */
    public function __construct(Carbon $createdAt, $distance)
    {
        $this->createdAt = $createdAt;
        $this->distance = $distance;
    }


    /**
     * @return Carbon
     */
    public function getCreatedDate()
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }
}
