<?php

include "./vendor/autoload.php";

$distanceLogs = new \Illuminate\Support\Collection([
    new \TrajectoryPrediction\DistanceLog(\Carbon\Carbon::create(2016, 5, 30), 10000),
    new \TrajectoryPrediction\DistanceLog(\Carbon\Carbon::create(2016, 6, 15), 11000),
    new \TrajectoryPrediction\DistanceLog(\Carbon\Carbon::create(2016, 6, 25), 13000),
    new \TrajectoryPrediction\DistanceLog(\Carbon\Carbon::create(2016, 10, 1), 25000),
    new \TrajectoryPrediction\DistanceLog(\Carbon\Carbon::create(2016, 10, 25), 25500),
]);

$calculator = new \TrajectoryPrediction\Calculator();
$trajectories = $calculator->generateTrajectories($distanceLogs);
$predictor = new \TrajectoryPrediction\Predictor();

dd($trajectories, $predictor->predict($trajectories, 30000, 32000));