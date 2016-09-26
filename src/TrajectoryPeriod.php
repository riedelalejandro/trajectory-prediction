<?php
namespace TrajectoryPrediction;

use Carbon\Carbon;

/**
 * Class TrajectoryPeriod.
 */
class TrajectoryPeriod
{
    /**
     * @var Carbon
     */
    private $startDate;
    /**
     * @var Carbon
     */
    private $endDate;
    /**
     * @var int
     */
    private $dailyDistance;

    /**
     * TrajectoryPeriod constructor.
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int $dailyDistance
     */
    public function __construct(Carbon $startDate, Carbon $endDate, $dailyDistance)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->dailyDistance = $dailyDistance;
    }

    /**
     * @return Carbon
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return Carbon
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return int
     */
    public function getDailyDistance()
    {
        return $this->dailyDistance;
    }

}
