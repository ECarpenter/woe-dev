<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
        'timezone' => "America/Los_Angeles"
    ];
});


$factory->define(App\Tenant::class, function (Faker\Generator $faker) {

	$user = factory(App\User::class)->create();
	$role = DB::table('roles')->where('name', '=', 'tenant')->pluck('id');
    $user->Roles()->attach($role);

    return [
        'unit' => $faker->buildingNumber,
        'company_name' => $faker->company,
        'job_title' => $faker->title,
        'property_id' => rand(1,5),
        'user_id' => $user->id,
        'tenant_system_id' =>"t".$faker->buildingNumber,
        'active' => true,
        'verified' => true,
        
    ];
});

$factory->define(App\Property::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->streetName,
        'property_system_id' => "f".$faker->buildingNumber,
        'address' => $faker->streetAddress,
        'owner' =>$faker->company,
    ];
});


