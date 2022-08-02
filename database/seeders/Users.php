<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

       DB::table('users')->insert([
           'nom'=>$faker->name(),
           'tel'=>$faker->e164PhoneNumber(),
           'sexe'=>"male",
           'ville'=>$faker->city(),
           'password'=>bcrypt('123456789'),
           
       ]);
    }
}
