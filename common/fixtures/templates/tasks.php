<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'dt_add' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
    'title' => $faker->sentence(5),
    'description' => $faker->paragraph(),
    'category_id' => $faker->numberBetween(1,8),
    'author_id' => $faker->numberBetween(1,20),
    'address' => $faker->streetAddress(),
    'location_id' => $faker->numberBetween(1,1000),
    'budget' => $faker->randomNumber(5,false),
    'due_date' => $faker->dateTimeBetween('now', '+ 3 week')->format('Y-m-d'),
    'executor_id' => $faker->numberBetween(1,20)
];
