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
    public function predict(Collection $trajectoryPeriods, $distanceFrom, $distanceTo)
    {
        $defaultMonthly = 1000;
        $initialDate = Carbon::now();
        $maxPeriodDate = $initialDate->copy()->lastOfMonth();
        $historyDate = $initialDate->copy()->addYear(-1);

        while ($distanceFrom < $distanceTo)
        {
            /** @var TrajectoryPeriod $trajectoryPeriod */
            $trajectoryPeriod = $trajectoryPeriods->filter(function(TrajectoryPeriod $trajectoryPeriod) use ($historyDate) {
                return $trajectoryPeriod->getYear() == $historyDate->year && $trajectoryPeriod->getMonth() == $historyDate->month;
            })->first();

            if ($trajectoryPeriod)
            {
                $distance = $trajectoryPeriod->getDistance();
            }
            else {
                $distance = $defaultMonthly;
            }

            $distanceFrom += $distance;

            $initialDate->addMonth();
        }
        return $initialDate;
    }
}
