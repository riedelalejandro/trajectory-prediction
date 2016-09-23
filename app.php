<?php

include './vendor/autoload.php';

$builder = \TrajectoryPrediction\Predictor::create();
$builder->withDefaultMonthly(1000);
$builder->withDistanceHistory(\Carbon\Carbon::create(2016, 9, 1), 15000);
$builder->andWithDistanceHistory(\Carbon\Carbon::create(2016, 9, 22), 16000);
dd($builder->getTrajectoryPeriods(), $builder->predict(16200));
