<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'dt_add' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
    'email' => $faker->email(),
    'name' => $faker->name(),
    'information' => $faker->paragraph(3),
    'birthday' => $faker->date('Y-m-d', '1988-12-11'),
    'address' => $faker->streetAddress(),
    'location_id' => $faker->numberBetween(1,1000),
    'password' => $faker->password(6,12),
    'phone' => $faker->e164PhoneNumber(),
    'skype' => $faker->word(),
    'dt_last_activity' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
    'role' => $faker->numberBetween(0,1)
];
