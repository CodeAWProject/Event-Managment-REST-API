<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     // Creating 200 fake events with the random users
    public function run(): void
    {
        $users = User::all();

        for($i = 0; $i < 200; $i++) {
            $user = $users->random();
            \App\Models\Event::factory([
                'user_id' => $user->id
            ])->create();
        }
    }
}
