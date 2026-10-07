<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->assignRole('super-admin');

        foreach ($this->contentSeeders() as $seeder) {
            $this->call($seeder);
        }
    }

    /**
     * Every seeder dropped into database/seeders/Content is picked up automatically,
     * so content streams never need to edit this file.
     *
     * @return list<class-string<Seeder>>
     */
    protected function contentSeeders(): array
    {
        $files = glob(database_path('seeders/Content/*Seeder.php')) ?: [];
        sort($files);

        return array_map(
            fn (string $file) => __NAMESPACE__.'\\Content\\'.basename($file, '.php'),
            $files,
        );
    }
}
