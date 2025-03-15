<?php
namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        City::create(['name' => 'Riyadh']);
        City::create(['name' => 'Jeddah']);
        City::create(['name' => 'Dammam']);
    }
}