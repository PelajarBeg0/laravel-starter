<?php

namespace Modules\Post\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Modules\Post\Models\Post;
use Modules\Post\Enums\PostStatus;
use Modules\Post\Enums\PostType;

class PostDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Authenticate as the first user to satisfy BaseModel's created_by requirement
        $user = User::first();
        Auth::login($user);

        $posts = [
            [
                'name' => 'Pembukaan Pendaftaran Peserta Didik Baru (PPDB) Tahun Ajaran 2024/2025',
                'intro' => 'SMPN 1 Kra resmi membuka pendaftaran peserta didik baru untuk tahun ajaran mendatang.',
                'content' => 'Pendaftaran Peserta Didik Baru (PPDB) di SMPN 1 Kra telah resmi dibuka mulai hari ini. Bagi orang tua dan calon siswa yang ingin bergabung, pendaftaran dapat dilakukan secara daring melalui website resmi sekolah atau datang langsung ke sekretariat pendaftaran di kampus sekolah. Pastikan semua dokumen persyaratan telah lengkap sebelum melakukan pendaftaran.',
                'category_id' => 2, // Pengumuman
            ],
            [
                'name' => 'Siswa SMPN 1 Kra Meraih Juara 1 Lomba Matematika Tingkat Provinsi',
                'intro' => 'Prestasi membanggakan kembali diraih oleh salah satu siswa bertalenta dari SMPN 1 Kra.',
                'content' => 'Kabar gembira datang dari ajang Olimpiade Matematika tingkat Provinsi. Ananda Ahmad Fauzi, siswa kelas VIII, berhasil menyabet medali emas dan menjadi Juara 1. Keberhasilan ini tidak lepas dari kerja keras ananda dan bimbingan dari para guru matematika. Semoga prestasi ini menjadi motivasi bagi siswa-siswi lainnya untuk terus berprestasi.',
                'category_id' => 3, // Prestasi
            ],
            [
                'name' => 'Kegiatan LDKS OSIS Masa Bakti 2024-2025 Berjalan Lancar',
                'intro' => 'Latihan Dasar Kepemimpinan Siswa (LDKS) dilaksanakan untuk membentuk karakter pemimpin masa depan.',
                'content' => 'Selama tiga hari berturut-turut, para pengurus OSIS baru mengikuti kegiatan LDKS yang berlokasi di area sekolah dan sekitarnya. Berbagai materi kepemimpinan, kerja sama tim, dan kedisiplinan diberikan oleh para narasumber yang kompeten. Diharapkan pengurus OSIS yang baru ini dapat mengemban amanah dengan baik dan membawa perubahan positif bagi sekolah.',
                'category_id' => 4, // Kegiatan Siswa
            ],
            [
                'name' => 'Pentingnya Literasi Digital di Era Pendidikan Modern',
                'intro' => 'Artikel dari Bapak Guru mengenai tantangan dan peluang literasi digital bagi siswa.',
                'content' => 'Di era digital saat ini, kemampuan untuk memilah dan memilih informasi menjadi sangat krusial. Literasi digital bukan hanya soal bisa menggunakan gawai, tapi bagaimana kita bertanggung jawab dalam berkomunikasi dan mencari informasi. Melalui artikel ini, kami mengajak para siswa untuk lebih bijak dalam bersosial media dan memanfaatkan teknologi untuk menunjang proses belajar mengajar.',
                'category_id' => 5, // Artikel Guru
            ],
            [
                'name' => 'Pelaksanaan Ujian Tengah Semester Genap Berbasis Komputer',
                'intro' => 'Sekolah kembali menerapkan sistem ujian berbasis komputer (CBT) untuk meminimalisir penggunaan kertas.',
                'content' => 'Ujian Tengah Semester (UTS) genap tahun ini kembali dilaksanakan dengan sistem CBT. Seluruh laboratorium komputer telah disiapkan dengan baik untuk mendukung kelancaran ujian. Para siswa diingatkan untuk selalu menjaga kejujuran dan ketertiban selama pelaksanaan ujian berlangsung.',
                'category_id' => 2, // Pengumuman
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['name' => $post['name']],
                array_merge($post, [
                    'status' => PostStatus::Published,
                    'type' => PostType::News,
                    'published_at' => now(),
                    'image' => 'https://picsum.photos/1200/630?random=' . rand(1, 100),
                ])
            );
        }

        // Add some random posts using factory for variety, but with Indonesian locale
        Post::factory()->count(10)->create([
            'status' => PostStatus::Published,
            'category_id' => rand(1, 6),
        ]);

        // Clear authentication after seeding
        Auth::logout();

        if (! app()->runningUnitTests()) {
            $this->command->info('Post Module Seeded with School Themes');
        }
    }
}
