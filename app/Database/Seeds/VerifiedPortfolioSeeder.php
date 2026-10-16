<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use RuntimeException;

/** Publishes the reviewed content bundle; never deletes existing records or files. */
class VerifiedPortfolioSeeder extends Seeder
{
    public function run()
    {
        $bundle = json_decode(file_get_contents(APPPATH . 'Content/portfolio.json'), true, 512, JSON_THROW_ON_ERROR);
        $now = date('Y-m-d H:i:s');
        $this->db->transException(true)->transStart();

        foreach (['projects', 'labs', 'documentation', 'blog_posts', 'gallery', 'achievements', 'documents', 'certificates', 'experiences', 'education'] as $table) {
            $this->db->table($table)->where('is_dummy', 1)->update(['status' => 'draft', 'updated_at' => $now]);
        }
        foreach (['social_links', 'skill_categories', 'skills', 'project_categories', 'lab_categories', 'blog_categories', 'blog_tags', 'gallery_categories'] as $table) {
            $this->db->table($table)->where('is_dummy', 1)->update(['status' => 'inactive', 'updated_at' => $now]);
        }

        $profile = $this->db->table('profiles')->where('deleted_at', null)->orderBy('id')->get()->getRowArray();
        $profileData = $bundle['profile'] + ['status' => 'active', 'is_dummy' => 0, 'updated_at' => $now];
        // Preserve a real uploaded portrait; remove only the known generated monogram.
        if (! $profile || str_contains((string) $profile['profile_image'], 'placeholder')) {
            $profileData['profile_image'] = null;
        }
        if ($profile) {
            $this->db->table('profiles')->where('id', $profile['id'])->update($profileData);
        } else {
            $this->db->table('profiles')->insert($profileData + ['created_at' => $now]);
        }

        foreach ($bundle['social_links'] as $row) {
            $this->upsert('social_links', 'platform', $row + ['status' => 'active', 'is_dummy' => 0], $now);
        }
        foreach ($bundle['categories'] as $table => $rows) {
            foreach ($rows as $row) {
                $this->upsert($table, 'slug', $row + ['status' => 'active', 'is_dummy' => 0], $now);
            }
        }
        foreach (['projects', 'labs', 'documentation', 'blog_posts', 'gallery'] as $table) {
            foreach ($bundle[$table] as $row) {
                if (isset($row['category_slug'])) {
                    $categoryTable = ['projects' => 'project_categories', 'labs' => 'lab_categories', 'blog_posts' => 'blog_categories', 'gallery' => 'gallery_categories'][$table];
                    $category = $this->db->table($categoryTable)->where('slug', $row['category_slug'])->get()->getRowArray();
                    $row['category_id'] = $category['id'];
                    unset($row['category_slug']);
                }
                if (isset($row['content_file'])) {
                    $path = realpath(APPPATH . 'Content/' . $row['content_file']);
                    if (! $path || ! str_starts_with($path, realpath(APPPATH . 'Content') . DIRECTORY_SEPARATOR)) {
                        throw new RuntimeException('Invalid article content path.');
                    }
                    $row['content'] = file_get_contents($path);
                    unset($row['content_file']);
                }
                $key = $table === 'gallery' ? 'title' : 'slug';
                $defaults = ['status' => 'published', 'is_dummy' => 0];
                if ($table !== 'gallery') {
                    $defaults['published_at'] = '2026-10-16 10:00:00';
                }
                $this->upsert($table, $key, $row + $defaults, $now);
            }
        }
        foreach ($bundle['settings'] as $key => $value) {
            $this->upsert('site_settings', 'setting_key', [
                'setting_key' => $key, 'setting_value' => $value, 'is_public' => 1,
                'type' => 'text', 'setting_group' => str_starts_with($key, 'seo_') ? 'seo' : 'general',
            ], $now);
        }

        // The source certificate is a training certificate, not a Cisco exam credential.
        $this->db->table('certificates')->where('slug', 'cisco-ccna-200-301')->update([
            'title' => 'Pelatihan Cisco CCNA 200-301 — IDN.ID', 'updated_at' => $now,
        ]);
        $this->db->table('experiences')->where('company', 'PT Adhi Persada Properti')->update([
            'start_date' => '2024-07-22', 'end_date' => '2024-12-20',
            'location' => null,
            'responsibilities' => "Instalasi dan troubleshooting LAN, WiFi, komputer, printer, dan CCTV.\nPenataan ruang server, rack mounting, dan cabling.\nPengembangan aplikasi PHP dan MySQL serta pengelolaan data aset.\nPenyusunan dokumentasi teknis dan pencatatan penanganan masalah.",
            'updated_at' => $now,
        ]);
        // The original seeder assigned the same proficiency label to every skill without a source.
        $this->db->table('skills')->where('level', 'Intermediate')->update(['level' => null, 'updated_at' => $now]);
        // Keep the original CV in downloads; omit automatically generated metric cards.
        $this->db->table('achievements')->whereIn('title', [
            'Penyelesaian 90% Gangguan Jaringan Harian',
            'Migrasi 500+ Data Aset',
            'Tingkat Kepuasan Pengguna 95%',
        ])->update(['status' => 'draft', 'updated_at' => $now]);
        $this->db->table('gallery')->where('title', 'Sertifikat CCNA 200-301 - IDN.ID')->update([
            'title' => 'Pelatihan Cisco CCNA 200-301 - IDN.ID', 'updated_at' => $now,
        ]);
        $this->db->transComplete();
    }

    private function upsert(string $table, string $key, array $row, string $now): void
    {
        $existing = $this->db->table($table)->where($key, $row[$key])->get()->getRowArray();
        if ($existing) {
            // Do not resurrect a record deliberately removed by the owner.
            if (! empty($existing['deleted_at'])) {
                return;
            }
            $this->db->table($table)->where('id', $existing['id'])->update($row + ['updated_at' => $now]);
        } else {
            $this->db->table($table)->insert($row + ['created_at' => $now, 'updated_at' => $now]);
        }
    }
}
