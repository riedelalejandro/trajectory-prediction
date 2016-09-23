# Trajectory Prediction

Predict a date when a distance will be completed . The prediction is based on monthly traveled distances or in a default monthly distance.

### Installation

Trajectory Prediction can be installed with composer.

```sh
$  composer require "riedelalejandro/trajectory-prediction":"~1.0"
```

### Usage

```php
$builder = \TrajectoryPrediction\Predictor::create();
// or
$builder = new \TrajectoryPrediction\Predictor();

// Used for periods without previous distance history
// How many kilometers monthly travel with my car?
$builder->withDefaultMonthly(1000);

// Add all previous distances traveled
// On 2016-04-15, my car had 10000km on his Speedometer
$builder->withDistanceHistory(\Carbon\Carbon::create(2016, 4, 15), 10000);

// On 2016-09-1, my car had 15000km on his Speedometer
$builder->andWithDistanceHistory(\Carbon\Carbon::create(2016, 9, 1), 15000);

// And Today my car has 15700km on his Speedometer
$builder->andWithDistanceHistory(\Carbon\Carbon::today(), 15700);

// Magic
// When my car will have 20000km
$predictDate = $builder->predict();
```

### Development

Want to contribute? Great!

Trajectory Prediction uses phpunit for testing and php-cs-fixer for code style.

Open your Terminal and run these commands.

Clone and install composer dependencies:

```sh
$ git clone git@github.com:riedelalejandro/trajectory-prediction.git
$ cd trajectory-prediction/
$ composer install
```

For code-styling:
```sh
$ ./vendor/bin/php-cs-fixer fix .
```

For run tests:
```sh
$ ./vendor/bin/phpunit
```

License
----

MIT
