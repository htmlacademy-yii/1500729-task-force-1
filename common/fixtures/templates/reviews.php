<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'dt_add' => $faker->date('Y-m-d H:i:s'),
    'content' => $faker->paragraph(2),
    'task_id' => $faker->numberBetween(2,11),
    'ratio' => $faker->numberBetween(1,5)
];
