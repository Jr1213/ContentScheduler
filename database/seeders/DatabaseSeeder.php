<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Models\User;
use App\PlatformTypeEnum;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        foreach (
            [
                'twitter',
                'instagram',
                'linkedin'
            ] as $platform
        ) {
            Platform::factory()->create([
                'name' => $platform,
                'type' => $platform
            ]);
        }
    }
}
