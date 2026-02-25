<?php

namespace Modules\Category\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Modules\Category\Models\Category;
use Modules\Category\Enums\CategoryStatus;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Authenticate as the first user to satisfy BaseModel's created_by requirement
        $user = User::first();
        Auth::login($user);

        $categories = [
            [
                'name' => 'Berita Sekolah',
                'description' => 'Informasi terbaru mengenai kegiatan dan kabar seputar sekolah.',
            ],
            [
                'name' => 'Pengumuman',
                'description' => 'Informasi resmi dari pihak sekolah untuk siswa dan orang tua.',
            ],
            [
                'name' => 'Prestasi',
                'description' => 'Daftar pencapaian dan prestasi siswa maupun guru.',
            ],
            [
                'name' => 'Kegiatan Siswa',
                'description' => 'Dokumentasi berbagai kegiatan ekstrakurikuler dan organisasi siswa.',
            ],
            [
                'name' => 'Artikel Guru',
                'description' => 'Tulisan-tulisan edukatif dan opini dari tenaga pendidik.',
            ],
            [
                'name' => 'Kurikulum',
                'description' => 'Informasi terkait sistem pembelajaran dan materi pelajaran.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'status' => CategoryStatus::Active, // Assuming Active is a case in CategoryStatus
                ]
            );
        }

        // Clear authentication after seeding
        Auth::logout();

        if (! app()->runningUnitTests()) {
            $this->command->info('Category Module Seeded with School Themes');
        }
    }
}
