<?php

namespace Test\Src;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use TrajectoryPrediction\Predictor;
use TrajectoryPrediction\PredictorException;

/**
 * Class PredictorTest.
 */
class PredictorTest extends TestCase
{
    /**
     * @var Generator
     */
    private $faker;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function test_predictor_without_default_monthly()
    {
        $this->expectException(PredictorException::class);

        Predictor::create()
            ->withDistanceHistory(Carbon::now(), $this->faker->numberBetween(0, 30000))
            ->predict($this->faker->numberBetween(30000, 50000));
    }

    public function test_predictor_without_distance_histories()
    {
        $this->expectException(PredictorException::class);

        Predictor::create()
            ->withDefaultMonthly($this->faker->numberBetween(50, 1000))
            ->predict($this->faker->numberBetween(30000, 50000));
    }

    public function test_predictor_success()
    {
        $builder = Predictor::create()
            ->withDefaultMonthly($this->faker->numberBetween(50, 1000))
            ->withDistanceHistory(Carbon::now(), $this->faker->numberBetween(0, 30000));

        $date = $builder->predict($this->faker->numberBetween(30000, 50000));
        $this->assertInstanceOf(Carbon::class, $date);
        $this->assertCount(1, $builder->getDistanceHistories()->values());
    }
}
