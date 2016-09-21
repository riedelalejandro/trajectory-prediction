<?php

namespace TrajectoryPrediction;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Predictor.
 */
class Predictor
{
    /**
     * @param Collection $trajectoryPeriods TrajectoryPeriod[]
     *
     * @return Carbon
     */
    public function predict(Collection $trajectoryPeriods)
    {
        return new Carbon();
    }
}
