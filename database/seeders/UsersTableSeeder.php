<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Edwin',
            'last_name'=>'Zapata',
            'email' => 'edwinrzc@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin00'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
