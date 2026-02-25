<?php

namespace Modules\Tag\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Modules\Tag\Models\Tag;
use Modules\Tag\Enums\TagStatus;

class TagDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Authenticate as the first user to satisfy BaseModel's created_by requirement
        $user = User::first();
        Auth::login($user);

        $tags = [
            'PPDB',
            'Kurikulum Merdeka',
            'Ekstrakurikuler',
            'OSIS',
            'Pramuka',
            'Ujian Semester',
            'Wisuda',
            'Karya Wisata',
            'Seminar',
            'Workshop Guru',
            'Kesehatan Sekolah',
            'Lomba',
            'Pentas Seni',
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['name' => $tag],
                [
                    'description' => 'Topik yang berkaitan dengan ' . $tag,
                    'status' => TagStatus::Active, // Assuming Active is a case in TagStatus
                ]
            );
        }

        // Clear authentication after seeding
        Auth::logout();

        if (! app()->runningUnitTests()) {
            $this->command->info('Tag Module Seeded with School Themes');
        }
    }
}
