<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->loadSeeder([
            UsersTableSeeder::class,
            EstadoSeeder::class,
            MetodoPagoSeeder::class
        ]);
    }


    private function loadSeeder(array $class)
    {
        foreach($class as $value)
        {
            $this->call([$value]);
        }
    }
}
