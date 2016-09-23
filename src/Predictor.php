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
     * @var int
     */
    private $defaultMonthly;
    /**
     * @var Collection
     */
    private $distanceHistories;
    /**
     * @var Collection|TrajectoryPeriod[]
     */
    private $trajectoryPeriods;

    /**
     * Predictor constructor.
     */
    public function __construct()
    {
        $this->distanceHistories = new Collection();
    }

    /**
     * @return Predictor
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return Predictor
     */
    public function then()
    {
        return $this;
    }

    /**
     * @param int $defaultMonthly
     *
     * @return Predictor
     */
    public function withDefaultMonthly($defaultMonthly)
    {
        $this->defaultMonthly = $defaultMonthly;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultMonthly()
    {
        return $this->defaultMonthly;
    }

    /**
     * @param Carbon $date
     * @param int    $distance
     *
     * @return Predictor
     */
    public function withDistanceHistory(Carbon $date, $distance)
    {
        $this->distanceHistories->push(new DistanceHistory($date, $distance));

        return $this;
    }
    /**
     * @param Carbon $date
     * @param int    $distance
     *
     * @return Predictor
     */
    public function andWithDistanceHistory(Carbon $date, $distance)
    {
        return $this->withDistanceHistory($date, $distance);
    }

    /**
     * @return Collection|DistanceHistory[]
     */
    public function getDistanceHistories()
    {
        return $this->distanceHistories;
    }

    /**
     * @return Collection|TrajectoryPeriod[]
     */
    public function getTrajectoryPeriods()
    {
        if (is_null($this->trajectoryPeriods)) {
            $this->calculateTrajectoryPeriods();
        }

        return $this->trajectoryPeriods;
    }

    /**
     * @return Predictor
     */
    public function calculateTrajectoryPeriods()
    {
        $trajectoryPeriods = new Collection();
        /** @var Carbon $previousCalculatedDate */
        $previousCalculatedDate = null;
        $previousTrajectory = null;

        /** @var DistanceHistory $distanceHistory */
        foreach ($this->getDistanceHistories() as $distanceHistory)
        {
            if (!$previousTrajectory)
            {
                $previousTrajectory = $distanceHistory->getDistance() - round($distanceHistory->getDate()->day * $this->getDefaultMonthly() / $distanceHistory->getDate()->daysInMonth);
            }

            if (!$previousCalculatedDate)
            {
                $previousCalculatedDate = $distanceHistory->getDate()->copy()->firstOfMonth();
            }

            $diffInKM = $distanceHistory->getDistance() - $previousTrajectory;
            $diffInDays = $previousCalculatedDate->diffInDays($distanceHistory->getDate()) + 1;

            while ($previousCalculatedDate->lessThan($distanceHistory->getDate()))
            {
                /** @var Carbon $lastPeriodDate */
                $lastPeriodDate = $previousCalculatedDate->copy()->lastOfMonth();
                if ($lastPeriodDate->greaterThan($distanceHistory->getDate()))
                {
                    $lastPeriodDate = $distanceHistory->getDate();
                }

                $trajectoryPeriod = $trajectoryPeriods->filter(function (TrajectoryPeriod $trajectoryPeriod) use ($previousCalculatedDate) {
                    return $trajectoryPeriod->getYear() == $previousCalculatedDate->year && $trajectoryPeriod->getMonth() == $previousCalculatedDate->month;
                })->first();

                if (!$trajectoryPeriod)
                {
                    $trajectoryPeriod = new TrajectoryPeriod($previousCalculatedDate->year, $previousCalculatedDate->month, 0);
                    $trajectoryPeriods->push($trajectoryPeriod);
                }

                $diff = round(($previousCalculatedDate->diffInDays($lastPeriodDate) + 1) * $diffInKM / $diffInDays);

                $trajectoryPeriod->increment($diff);

                $previousCalculatedDate = $lastPeriodDate->addDay();
            }

            $previousTrajectory = $distanceHistory->getDistance();
            $previousCalculatedDate = $distanceHistory->getDate();
        }

        $trajectoryPeriods->pop(); // Delete last period calculated because provably is an incomplete period

        $this->trajectoryPeriods = $trajectoryPeriods;

        return $this;
    }

    /**
     * @param int $distanceTo
     *
     * @return Carbon
     */
    public function predict($distanceTo)
    {
        if ($this->getDistanceHistories()->isEmpty())
        {
            throw new PredictorException('Missing distance histories');
        }

        if (!$this->getDefaultMonthly())
        {
            throw new PredictorException('Missing default monthly');
        }

        /** @var DistanceHistory $lastUpdate */
        $lastUpdate = $this->getDistanceHistories()->last();
        $distanceFrom = $lastUpdate->getDistance();
        $initialDate = $lastInitialDate = $lastUpdate->getDate();

        while ($distanceFrom < $distanceTo)
        {
            $initialDate = $lastInitialDate;
            $maxPeriodDate = $initialDate->copy()->lastOfMonth();
            $historyDate = Carbon::create(null, $initialDate->month)->addYear(-1);

            $days = $maxPeriodDate->diffInDays($initialDate) + 1;

            /** @var TrajectoryPeriod $trajectoryPeriod */
            $trajectoryPeriod = $this->getTrajectoryPeriods()->filter(function (TrajectoryPeriod $trajectoryPeriod) use ($historyDate) {
                return $trajectoryPeriod->getYear() == $historyDate->year && $trajectoryPeriod->getMonth() == $historyDate->month;
            })->first();

            if ($trajectoryPeriod)
            {
                $dailyDistance = $trajectoryPeriod->getDailyDistance();
            }
            else
            {
                $dailyDistance = round($this->getDefaultMonthly() / $initialDate->daysInMonth);
            }

            $neededDays = ($distanceTo - $distanceFrom) / $dailyDistance;
            $days = min($neededDays, $days);

            $distanceFrom += $dailyDistance * $days;

            $lastInitialDate->addDays($days);
        }

        return $initialDate;
    }
}
