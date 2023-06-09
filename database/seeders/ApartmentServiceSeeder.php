<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Service;

use Faker\Generator as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
        {
            $apartments = Apartment::all(); 
            $services = Service::all()->pluck('id');

            foreach($apartments as $apartment) {
            $apartment->services()->attach($faker->randomElements($services, 8));
        }

                
        }
}