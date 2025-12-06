<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \Laravolt\Indonesia\Seeds\ProvincesSeeder::class,
            \Laravolt\Indonesia\Seeds\CitiesSeeder::class,
            \Laravolt\Indonesia\Seeds\DistrictsSeeder::class,
            \Laravolt\Indonesia\Seeds\VillagesSeeder::class,
        ]);
    }
}
