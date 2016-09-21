<?php

namespace TrajectoryPrediction;

use Carbon\Carbon;

/**
 * Interface DistanceLog
 * @package TrajectoryPrediction
 */
interface DistanceLog
{
    /**
     * @return Carbon
     */
    public function getCreatedDate();

    /**
     * @return int
     */
    public function getDistance();
}
