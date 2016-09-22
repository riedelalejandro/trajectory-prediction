<?php

namespace TrajectoryPrediction;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Calculator.
 */
class Calculator
{
    /**
     * @param Collection $distanceLogs DistanceLog[]
     *
     * @return Collection|TrajectoryPeriod[]
     */
    public function generateTrajectories(Collection $distanceLogs)
    {
        $defaultMonthly = 1000;

        $trajectoryPeriods = new Collection();
        /** @var Carbon $previousCalculatedDate */
        $previousCalculatedDate = null;
        $previousTrajectory = null;

        /** @var DistanceLog $distanceLog */
        foreach ($distanceLogs as $distanceLog)
        {
            if (!$previousTrajectory)
            {
                $previousTrajectory = $distanceLog->getDistance() - round($distanceLog->getCreatedDate()->day * $defaultMonthly / $distanceLog->getCreatedDate()->daysInMonth);
            }

            if (!$previousCalculatedDate)
            {
                $previousCalculatedDate = $distanceLog->getCreatedDate()->copy()->firstOfMonth();
            }

            $diffInKM = $distanceLog->getDistance() - $previousTrajectory;
            $diffInDays = $previousCalculatedDate->diffInDays($distanceLog->getCreatedDate()) + 1;
            while ($previousCalculatedDate->lessThan($distanceLog->getCreatedDate()))
            {
                $lastPeriodDate = $previousCalculatedDate->copy()->lastOfMonth();
                if ($lastPeriodDate->greaterThan($distanceLog->getCreatedDate()))
                {
                    $lastPeriodDate = $distanceLog->getCreatedDate();
                }

//                 Busco si ya existe el Período
                $trajectoryPeriod = $trajectoryPeriods->filter(function(TrajectoryPeriod $trajectoryPeriod) use ($previousCalculatedDate) {
                    return $trajectoryPeriod->getYear() == $previousCalculatedDate->year && $trajectoryPeriod->getMonth() == $previousCalculatedDate->month;
                })->first();

                // Si no existe, creo el período
                if (!$trajectoryPeriod)
                {
                    $trajectoryPeriod = new TrajectoryPeriod($previousCalculatedDate->year, $previousCalculatedDate->month, 0);
                    $trajectoryPeriods->push($trajectoryPeriod);
                }

                $diff = round(($previousCalculatedDate->diffInDays($lastPeriodDate) + 1) * $diffInKM / $diffInDays);

                $trajectoryPeriod->increment($diff);

                $previousCalculatedDate = $lastPeriodDate->addDay();
            }

            $previousTrajectory = $distanceLog->getDistance();
            $previousCalculatedDate = $distanceLog->getCreatedDate();
        }

        return $trajectoryPeriods;
    }
}
