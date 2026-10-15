<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * PortfolioContentSeeder
 *
 * Seeds all portfolio content using REAL data extracted from data_konten/
 * and clearly-marked DUMMY/PLACEHOLDER data for sections that lack real content.
 *
 * Real data sources:
 *   - CV_MUHAMMAD_AZRIL_ACHDI (2).pdf
 *   - IDN.ID_AZRIL_page-0001.jpg               (CCNA 200-301)
 *   - Sertifikat Prakerin - Muhammad Azril Achdi (Prakerin PT Adhi Persada Properti)
 *   - Sertifikat TOEFL MABA 2025 Informatics    (English Placement Test UAD)
 *   - sertifikat_course_615_5537582 (Dicoding – Belajar Dasar Data Science)
 *   - sertifikat_course_653_5537582 (Dicoding – Belajar Dasar AI)
 *
 * Every dummy record has is_dummy = 1.
 * Every real  record has is_dummy = 0.
 */
class PortfolioContentSeeder extends Seeder
{
    private string $now;

    public function run()
    {
        $this->now = date('Y-m-d H:i:s');

        $this->seedProfile();
        $this->seedSocialLinks();
        $this->seedSkills();
        $this->seedExperience();
        $this->seedEducation();
        $this->seedCertificates();
        $this->seedProjects();
        $this->seedLabs();
        $this->seedAchievements();
        $this->seedDocuments();
        $this->seedDocumentation();
        $this->seedBlog();
        $this->seedGallery();
        $this->seedSiteSettings();
    }

    // ─── PROFILE (REAL) ──────────────────────────────────────────────

    private function seedProfile(): void
    {
        $db = $this->db;
        if ($db->table('profiles')->countAllResults() > 0) {
            return;
        }

        $db->table('profiles')->insert([
            'name'               => 'Muhammad Azril Achdi',
            'professional_title' => 'IT Support & Web Developer',
            'headline'           => 'S1 Informatika | IT Support & Web Developer | Network & Infrastructure',
            'short_bio'          => 'Mahasiswa S1 Informatika dengan pengalaman sebagai IT Support dan Web Developer, terbiasa menangani instalasi, konfigurasi, dan troubleshooting perangkat jaringan (LAN, WiFi, IP), pemeliharaan server, serta dukungan teknis langsung ke end-user.',
            'full_bio'           => 'Mahasiswa S1 Informatika dengan pengalaman sebagai IT Support dan Web Developer, terbiasa menangani instalasi, konfigurasi, dan troubleshooting perangkat jaringan (LAN, WiFi, IP), pemeliharaan server, serta dukungan teknis langsung ke end-user. Memiliki pengalaman rack mounting, cabling, dan pengelolaan infrastruktur IT, ditunjang kemampuan komunikasi bahasa Inggris dan dokumentasi teknis yang rapi. Terbiasa bekerja mandiri, responsif, dan berorientasi pada penyelesaian masalah secara efisien.',
            'profile_image'      => 'uploads/profile/profile-placeholder-maa.png',
            'location'           => 'Perumahan Bekasi Timur Permai, Tambun Selatan',
            'email'              => 'muhammad.azril.achdi@gmail.com',
            'phone'              => '081282150702',
            'career_focus'       => 'IT Support, Web Development, Network Infrastructure',
            'resume_file'        => 'uploads/documents/CV_Muhammad_Azril_Achdi.pdf',
            'availability'       => 'Open to opportunities',
            'github_url'         => null,
            'linkedin_url'       => null,
            'website_url'        => null,
            'status'             => 'active',
            'is_dummy'           => 0,
            'created_at'         => $this->now,
            'updated_at'         => $this->now,
        ]);
    }

    // ─── SOCIAL LINKS (PLACEHOLDER – no URLs in CV) ──────────────────

    private function seedSocialLinks(): void
    {
        $db = $this->db;
        if ($db->table('social_links')->countAllResults() > 0) {
            return;
        }

        $links = [
            ['platform' => 'Email',    'label' => 'muhammad.azril.achdi@gmail.com', 'url' => 'mailto:muhammad.azril.achdi@gmail.com', 'icon' => 'fas fa-envelope',   'sort_order' => 1, 'is_dummy' => 0],
            ['platform' => 'GitHub',   'label' => '[Tambahkan GitHub URL]',          'url' => '#',                                      'icon' => 'fab fa-github',     'sort_order' => 2, 'is_dummy' => 1],
            ['platform' => 'LinkedIn', 'label' => '[Tambahkan LinkedIn URL]',        'url' => '#',                                      'icon' => 'fab fa-linkedin',   'sort_order' => 3, 'is_dummy' => 1],
            ['platform' => 'Instagram','label' => '[Tambahkan Instagram URL]',       'url' => '#',                                      'icon' => 'fab fa-instagram',  'sort_order' => 4, 'is_dummy' => 1],
        ];

        foreach ($links as $link) {
            $db->table('social_links')->insert(array_merge($link, [
                'status'     => 'active',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
        }
    }

    // ─── SKILLS (REAL from CV) ───────────────────────────────────────

    private function seedSkills(): void
    {
        $db = $this->db;
        if ($db->table('skills')->countAllResults() > 0) {
            return;
        }

        // Categories (all REAL – from CV)
        $categories = [
            ['name' => 'Jaringan & Infrastruktur',   'slug' => 'jaringan-infrastruktur',   'sort_order' => 1, 'is_dummy' => 0],
            ['name' => 'Web Development',             'slug' => 'web-development',           'sort_order' => 2, 'is_dummy' => 0],
            ['name' => 'Tools & Software',            'slug' => 'tools-software',            'sort_order' => 3, 'is_dummy' => 0],
            ['name' => 'Soft Skills',                 'slug' => 'soft-skills',               'sort_order' => 4, 'is_dummy' => 0],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $db->table('skill_categories')->insert(array_merge($cat, [
                'status'     => 'active',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
            $catIds[$cat['slug']] = $db->insertID();
        }

        // Skills (REAL – all from CV)
        $skills = [
            // Jaringan & Infrastruktur
            ['category' => 'jaringan-infrastruktur', 'name' => 'Jaringan Komputer Dasar (IP, LAN, WiFi)',        'level' => 'Intermediate', 'sort_order' => 1],
            ['category' => 'jaringan-infrastruktur', 'name' => 'Troubleshooting Koneksi',                       'level' => 'Intermediate', 'sort_order' => 2],
            ['category' => 'jaringan-infrastruktur', 'name' => 'Server & Perangkat Jaringan (Rack Mounting, Cabling)', 'level' => 'Intermediate', 'sort_order' => 3],
            ['category' => 'jaringan-infrastruktur', 'name' => 'Hardware Support (PC, Printer, CCTV)',           'level' => 'Intermediate', 'sort_order' => 4],
            ['category' => 'jaringan-infrastruktur', 'name' => 'Instalasi & Konfigurasi Windows/Office',        'level' => 'Intermediate', 'sort_order' => 5],
            // Web Development
            ['category' => 'web-development', 'name' => 'PHP (Native, CodeIgniter 3 & 4, Laravel)',            'level' => 'Intermediate', 'sort_order' => 1],
            ['category' => 'web-development', 'name' => 'MySQL (Database Management)',                         'level' => 'Intermediate', 'sort_order' => 2],
            // Tools & Software
            ['category' => 'tools-software', 'name' => 'Git (Version Control)',                                 'level' => 'Intermediate', 'sort_order' => 1],
            ['category' => 'tools-software', 'name' => 'Dokumentasi Teknis',                                    'level' => 'Intermediate', 'sort_order' => 2],
            // Soft Skills
            ['category' => 'soft-skills', 'name' => 'Problem Solving',                                          'level' => 'Intermediate', 'sort_order' => 1],
            ['category' => 'soft-skills', 'name' => 'Bahasa Inggris (Lisan & Tulisan)',                         'level' => 'Intermediate', 'sort_order' => 2],
            ['category' => 'soft-skills', 'name' => 'Komunikasi Efektif & Kerja Tim',                          'level' => 'Intermediate', 'sort_order' => 3],
            ['category' => 'soft-skills', 'name' => 'Manajemen Waktu & Organisasi',                            'level' => 'Intermediate', 'sort_order' => 4],
        ];

        foreach ($skills as $skill) {
            $db->table('skills')->insert([
                'category_id' => $catIds[$skill['category']],
                'name'        => $skill['name'],
                'level'       => $skill['level'],
                'sort_order'  => $skill['sort_order'],
                'status'      => 'active',
                'is_dummy'    => 0,
                'created_at'  => $this->now,
                'updated_at'  => $this->now,
            ]);
        }
    }

    // ─── EXPERIENCE (REAL from CV) ───────────────────────────────────

    private function seedExperience(): void
    {
        $db = $this->db;
        if ($db->table('experiences')->countAllResults() > 0) {
            return;
        }

        $db->table('experiences')->insert([
            'company'         => 'PT Adhi Persada Properti',
            'position'        => 'Web Development Intern & IT Support',
            'employment_type' => 'PKL/Magang',
            'location'        => 'Bekasi',
            'start_date'      => '2024-06-01',
            'end_date'        => '2024-12-01',
            'is_current'      => 0,
            'description'     => 'PKL/Magang di PT Adhi Persada Properti (A Subsidiary of PT Adhi Karya (Persero) Tbk.). Berperan sebagai Web Development Intern dan IT Support.',
            'responsibilities'=> "Menyelesaikan 90% gangguan jaringan & komputer harian (LAN, WiFi, konektivitas) secara terstruktur dan tepat waktu\nMelakukan instalasi, konfigurasi, dan troubleshooting hardware (PC, printer, perangkat jaringan) untuk mendukung operasional end-user\nMelakukan maintenance dan penataan ruang server, termasuk manajemen kabel (cabling) dan sistem monitoring suhu/perangkat untuk menjaga stabilitas infrastruktur IT\nInstalasi dan pemeliharaan perangkat (CCTV, printer, LAN) serta koordinasi lintas divisi dengan tingkat kepuasan pengguna 95%\nMenyusun dokumentasi teknis sistem, alur kerja, serta pencatatan permasalahan dan solusi (troubleshooting log)\nMengembangkan dan mengoptimalkan sistem berbasis web (frontend & backend) menggunakan PHP dan MySQL\nMigrasi dan pengelolaan 500+ data aset ke sistem baru, meningkatkan efisiensi hingga 30%\nBerkolaborasi dengan tim IT dan operasional dalam mendukung kebutuhan sistem",
            'achievements'    => null,
            'technologies'    => 'PHP, MySQL, LAN, WiFi, Windows, CCTV, Printer',
            'sort_order'      => 1,
            'status'          => 'published',
            'is_dummy'        => 0,
            'created_at'      => $this->now,
            'updated_at'      => $this->now,
        ]);
    }

    // ─── EDUCATION (REAL from CV) ────────────────────────────────────

    private function seedEducation(): void
    {
        $db = $this->db;
        if ($db->table('education')->countAllResults() > 0) {
            return;
        }

        $entries = [
            [
                'institution' => 'Universitas Ahmad Dahlan',
                'program'     => 'S1 Informatika',
                'degree'      => 'S1',
                'major'       => 'Informatika',
                'start_date'  => '2025-09-01',
                'end_date'    => null,
                'is_current'  => 1,
                'description' => 'Mahasiswa S1 Informatika',
                'sort_order'  => 1,
                'status'      => 'published',
                'is_dummy'    => 0,
            ],
            [
                'institution' => 'SMK Telekomunikasi Telesandi Bekasi',
                'program'     => 'Teknik Komputer dan Jaringan',
                'degree'      => 'SMK',
                'major'       => 'Teknik Komputer dan Jaringan',
                'start_date'  => '2022-07-01',
                'end_date'    => '2025-06-01',
                'is_current'  => 0,
                'description' => 'Lulusan jurusan Teknik Komputer dan Jaringan',
                'sort_order'  => 2,
                'status'      => 'published',
                'is_dummy'    => 0,
            ],
        ];

        foreach ($entries as $entry) {
            $db->table('education')->insert(array_merge($entry, [
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
        }
    }

    // ─── CERTIFICATES (REAL from data_konten) ────────────────────────

    private function seedCertificates(): void
    {
        $db = $this->db;
        if ($db->table('certificates')->countAllResults() > 0) {
            return;
        }

        // Categories
        $categories = [
            ['name' => 'Networking',      'slug' => 'networking',      'sort_order' => 1, 'is_dummy' => 0],
            ['name' => 'Data Science',    'slug' => 'data-science',    'sort_order' => 2, 'is_dummy' => 0],
            ['name' => 'Artificial Intelligence', 'slug' => 'artificial-intelligence', 'sort_order' => 3, 'is_dummy' => 0],
            ['name' => 'English',         'slug' => 'english',         'sort_order' => 4, 'is_dummy' => 0],
            ['name' => 'Internship',      'slug' => 'internship',      'sort_order' => 5, 'is_dummy' => 0],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $db->table('certificate_categories')->insert(array_merge($cat, [
                'status'     => 'active',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
            $catIds[$cat['slug']] = $db->insertID();
        }

        $certificates = [
            [
                'category_id'       => $catIds['networking'],
                'title'             => 'Cisco CCNA 200-301',
                'slug'              => 'cisco-ccna-200-301',
                'issuer'            => 'IDN.ID Training Center',
                'credential_id'     => 'CERT0701923105',
                'description'       => 'Completed a 32 hours program of Cisco CCNA Training at the IDN.ID Training Center, Jakarta, on 31 Agustus, 1, 2, 3 September 2026. Covers Network Fundamental, Network Access, IP Connectivity, IP Services, Security Fundamentals, and Automation and Programmability.',
                'skills_related'    => 'Network Fundamental, Network Access, IP Connectivity, IP Services, Security Fundamentals, Automation and Programmability',
                'issue_date'        => '2026-09-03',
                'expiration_date'   => null,
                'certificate_image' => 'uploads/certificates/images/ccna-200-301-idn.jpg',
                'certificate_file'  => null,
                'verification_url'  => null,
                'sort_order'        => 1,
                'status'            => 'published',
                'is_dummy'          => 0,
            ],
            [
                'category_id'       => $catIds['internship'],
                'title'             => 'Sertifikat Praktik Kerja Industri (Prakerin)',
                'slug'              => 'sertifikat-prakerin-adhi-persada-properti',
                'issuer'            => 'PT Adhi Persada Properti',
                'credential_id'     => null,
                'description'       => 'Sertifikat Praktik Kerja Industri (Prakerin) di PT Adhi Persada Properti (A Subsidiary of PT Adhi Karya (Persero) Tbk.), periode 22 Juli 2024 - 20 Desember 2024. Ditandatangani oleh Metcis Marma, Manager Biro Keuangan, Manajemen Risiko & Human Capital.',
                'skills_related'    => 'IT Support, Web Development, Network Troubleshooting',
                'issue_date'        => '2024-12-31',
                'expiration_date'   => null,
                'certificate_image' => 'uploads/certificates/images/prakerin-adhi-persada.jpg',
                'certificate_file'  => null,
                'verification_url'  => null,
                'sort_order'        => 2,
                'status'            => 'published',
                'is_dummy'          => 0,
            ],
            [
                'category_id'       => $catIds['english'],
                'title'             => 'English Placement Test Certificate (EPT/ADEPT)',
                'slug'              => 'english-placement-test-uad-2025',
                'issuer'            => 'Universitas Ahmad Dahlan - Ahmad Dahlan Language Center',
                'credential_id'     => 'U10/50.III/D.65/X/2025',
                'description'       => 'Ahmad Dahlan English Proficiency Test (ADEPT). Score: Listening Comprehension 52, Structure & Written Expression 61, Reading Comprehension 59. Total: 574. Student ID: 2500018036, Program: Informatics.',
                'skills_related'    => 'English Proficiency, Listening, Reading, Writing',
                'issue_date'        => '2025-10-09',
                'expiration_date'   => null,
                'certificate_image' => 'uploads/certificates/images/toefl-uad-2025.jpg',
                'certificate_file'  => null,
                'verification_url'  => null,
                'sort_order'        => 3,
                'status'            => 'published',
                'is_dummy'          => 0,
            ],
            [
                'category_id'       => $catIds['data-science'],
                'title'             => 'Belajar Dasar Data Science',
                'slug'              => 'belajar-dasar-data-science-dicoding',
                'issuer'            => 'Dicoding Indonesia',
                'credential_id'     => '98XWOJN29ZM3',
                'description'       => 'Kelas dasar data science yang mencakup The Power of Data, Fundamental Data Science, Menjelajahi Analisis Data, Teknologi dan Tools Pendukung Data Science, Machine Learning untuk Data Science, dan portofolio. Total 11 jam pembelajaran.',
                'skills_related'    => 'Data Science, Machine Learning, Analisis Data, SQL, Python, R',
                'issue_date'        => '2025-12-13',
                'expiration_date'   => '2028-12-13',
                'certificate_image' => null,
                'certificate_file'  => 'uploads/certificates/files/dicoding-dasar-data-science.pdf',
                'verification_url'  => 'https://dicoding.com/certificates/98XWOJN29ZM3',
                'sort_order'        => 4,
                'status'            => 'published',
                'is_dummy'          => 0,
            ],
            [
                'category_id'       => $catIds['artificial-intelligence'],
                'title'             => 'Belajar Dasar AI',
                'slug'              => 'belajar-dasar-ai-dicoding',
                'issuer'            => 'Dicoding Indonesia',
                'credential_id'     => '4EXG3GMDDZRL',
                'description'       => 'Kelas dasar AI yang mencakup Berkenalan dengan AI, Data untuk AI, Pengantar Machine Learning, dan Deep Learning Untuk Semua Orang. Total 10 jam pembelajaran.',
                'skills_related'    => 'Artificial Intelligence, Machine Learning, Deep Learning',
                'issue_date'        => '2025-12-15',
                'expiration_date'   => '2028-12-15',
                'certificate_image' => null,
                'certificate_file'  => 'uploads/certificates/files/dicoding-dasar-ai.pdf',
                'verification_url'  => 'https://dicoding.com/certificates/4EXG3GMDDZRL',
                'sort_order'        => 5,
                'status'            => 'published',
                'is_dummy'          => 0,
            ],
        ];

        foreach ($certificates as $cert) {
            $db->table('certificates')->insert(array_merge($cert, [
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
        }
    }

    // ─── PROJECTS (DUMMY – no project data in data_konten) ───────────

    private function seedProjects(): void
    {
        $db = $this->db;
        if ($db->table('projects')->countAllResults() > 0) {
            return;
        }

        // Categories
        $categories = [
            ['name' => 'Web Application', 'slug' => 'web-application', 'sort_order' => 1, 'is_dummy' => 0],
            ['name' => 'Networking',      'slug' => 'networking',      'sort_order' => 2, 'is_dummy' => 0],
            ['name' => 'IT Support',      'slug' => 'it-support',      'sort_order' => 3, 'is_dummy' => 0],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $db->table('project_categories')->insert(array_merge($cat, [
                'status'     => 'active',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
            $catIds[$cat['slug']] = $db->insertID();
        }

        $projects = [
            [
                'category_id'       => $catIds['web-application'],
                'title'             => 'Asset Management System [PLACEHOLDER]',
                'slug'              => 'asset-management-system-placeholder',
                'short_description' => '[PLACEHOLDER] Contoh project web application untuk manajemen aset. Data ini bersifat sementara dan akan diganti dengan project nyata.',
                'description'       => '<p><strong>[PLACEHOLDER PROJECT]</strong></p><p>Ini adalah contoh project placeholder untuk menguji tampilan portfolio. Konten akan diganti dengan deskripsi project nyata nantinya.</p>',
                'problem'           => '[PLACEHOLDER] Banyak aset IT perlu dicatat rapi agar status, lokasi, dan PIC mudah dilacak.',
                'solution'          => '[PLACEHOLDER] Sistem demo ini menggambarkan modul inventory, pencarian aset, dan ringkasan dashboard.',
                'architecture'      => '[PLACEHOLDER] Web app MVC berbasis CodeIgniter 4 dengan MySQL dan area AdminLTE.',
                'features'          => "[PLACEHOLDER]\n- CRUD aset\n- Kategori dan status aset\n- Dashboard ringkas\n- Export data",
                'technologies'      => 'PHP, CodeIgniter, MySQL',
                'challenges'        => '[PLACEHOLDER] Menjaga data aset tetap konsisten saat proses migrasi.',
                'result'            => '[PLACEHOLDER] Contoh tampilan project siap diganti dengan project asli.',
                'lessons_learned'   => '[PLACEHOLDER] Struktur database dan validasi form penting sejak awal.',
                'thumbnail'         => 'uploads/projects/asset-management-system-placeholder.png',
                'images'            => [
                    ['image' => 'uploads/projects/screenshots/asset-management-system-placeholder-01.png', 'caption' => 'Demo screenshot placeholder asset management.'],
                ],
                'status'            => 'published',
                'is_featured'       => 1,
                'is_dummy'          => 1,
                'sort_order'        => 1,
                'published_at'      => $this->now,
            ],
            [
                'category_id'       => $catIds['web-application'],
                'title'             => 'Portfolio Website [PLACEHOLDER]',
                'slug'              => 'portfolio-website-placeholder',
                'short_description' => '[PLACEHOLDER] Contoh project portfolio website. Data ini bersifat sementara.',
                'description'       => '<p><strong>[PLACEHOLDER PROJECT]</strong></p><p>Project contoh untuk layout testing. Akan diganti dengan data nyata.</p>',
                'problem'           => '[PLACEHOLDER] Portfolio membutuhkan admin panel agar konten bisa dikelola tanpa edit file.',
                'solution'          => '[PLACEHOLDER] Website demo dengan CI4, AdminLTE, CRUD konten, dan public homepage dinamis.',
                'architecture'      => '[PLACEHOLDER] Controller mengambil data dari model, view publik menjaga karakter template asli.',
                'features'          => "[PLACEHOLDER]\n- Dynamic homepage\n- Admin CRUD\n- Upload image/file\n- SEO basic",
                'technologies'      => 'PHP, CodeIgniter 4, MySQL, AdminLTE, HTML, CSS, JavaScript',
                'challenges'        => '[PLACEHOLDER] Menjaga template public tetap mirip saat data berubah dinamis.',
                'result'            => '[PLACEHOLDER] Struktur portfolio siap diisi konten nyata melalui dashboard admin.',
                'lessons_learned'   => '[PLACEHOLDER] Path upload dan fallback image harus konsisten dari awal.',
                'thumbnail'         => 'uploads/projects/portfolio-website-placeholder.png',
                'images'            => [
                    ['image' => 'uploads/projects/screenshots/portfolio-website-placeholder-01.png', 'caption' => 'Demo screenshot placeholder portfolio website.'],
                ],
                'status'            => 'published',
                'is_featured'       => 1,
                'is_dummy'          => 1,
                'sort_order'        => 2,
                'published_at'      => $this->now,
            ],
            [
                'category_id'       => $catIds['networking'],
                'title'             => 'Network Monitoring Dashboard [PLACEHOLDER]',
                'slug'              => 'network-monitoring-dashboard-placeholder',
                'short_description' => '[PLACEHOLDER] Contoh project network monitoring. Data ini bersifat sementara.',
                'description'       => '<p><strong>[PLACEHOLDER PROJECT]</strong></p><p>Contoh project placeholder untuk kategori networking.</p>',
                'problem'           => '[PLACEHOLDER] Perangkat jaringan perlu dipantau agar gangguan lebih cepat terlihat.',
                'solution'          => '[PLACEHOLDER] Dashboard demo untuk status device, grafik trafik, dan alert sederhana.',
                'architecture'      => '[PLACEHOLDER] Monitoring data masuk ke backend, lalu divisualisasikan ke dashboard web.',
                'features'          => "[PLACEHOLDER]\n- Device status\n- Traffic overview\n- Alert card\n- Summary report",
                'technologies'      => 'MikroTik, SNMP, PHP',
                'challenges'        => '[PLACEHOLDER] Menyederhanakan data monitoring agar mudah dibaca user.',
                'result'            => '[PLACEHOLDER] Cover project dan detail page sudah punya visual demo.',
                'lessons_learned'   => '[PLACEHOLDER] Visualisasi harus ringkas dan fokus pada status penting.',
                'thumbnail'         => 'uploads/projects/network-monitoring-dashboard-placeholder.png',
                'images'            => [
                    ['image' => 'uploads/projects/screenshots/network-monitoring-dashboard-placeholder-01.png', 'caption' => 'Demo screenshot placeholder network monitoring dashboard.'],
                ],
                'status'            => 'published',
                'is_featured'       => 1,
                'is_dummy'          => 1,
                'sort_order'        => 3,
                'published_at'      => $this->now,
            ],
        ];

        foreach ($projects as $project) {
            $images = $project['images'] ?? [];
            unset($project['images']);

            $db->table('projects')->insert(array_merge($project, [
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));

            $projectId = $db->insertID();
            foreach ($images as $index => $image) {
                $db->table('project_images')->insert([
                    'project_id'  => $projectId,
                    'image'       => $image['image'],
                    'caption'     => $image['caption'] ?? null,
                    'sort_order'  => $index + 1,
                    'is_featured' => $index === 0 ? 1 : 0,
                    'created_at'  => $this->now,
                ]);
            }
        }
    }

    // ─── LABS (DUMMY – no lab data in data_konten) ───────────────────

    private function seedLabs(): void
    {
        $db = $this->db;
        if ($db->table('labs')->countAllResults() > 0) {
            return;
        }

        // Categories
        $categories = [
            ['name' => 'Routing & Switching', 'slug' => 'routing-switching', 'sort_order' => 1, 'is_dummy' => 0],
            ['name' => 'VPN',                 'slug' => 'vpn',               'sort_order' => 2, 'is_dummy' => 0],
            ['name' => 'VLAN',                'slug' => 'vlan',              'sort_order' => 3, 'is_dummy' => 0],
            ['name' => 'Monitoring',          'slug' => 'monitoring',        'sort_order' => 4, 'is_dummy' => 0],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $db->table('lab_categories')->insert(array_merge($cat, [
                'status'     => 'active',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
            $catIds[$cat['slug']] = $db->insertID();
        }

        $labs = [
            [
                'category_id'       => $catIds['vlan'],
                'title'             => 'VLAN Segmentation Lab [DEMO LAB]',
                'slug'              => 'vlan-segmentation-lab-demo',
                'short_description' => '[DEMO LAB] Contoh lab VLAN segmentation untuk testing interface. Data ini placeholder.',
                'objective'         => 'Memahami konsep VLAN segmentation dan inter-VLAN routing.',
                'description'       => '<p><strong>[DEMO LAB - PLACEHOLDER]</strong></p><p>Ini adalah contoh lab placeholder untuk menguji tampilan lab di portfolio. Konten akan diganti dengan lab nyata.</p>',
                'topology'          => '[DEMO LAB] Internet - Router - Core Switch - VLAN 10/VLAN 20 clients.',
                'devices'           => '1 router, 1 managed switch, 2 client PCs. [DEMO]',
                'hardware'          => 'Router, managed switch, Ethernet cabling. [DEMO]',
                'software'          => 'RouterOS / switch OS simulator. [DEMO]',
                'ip_addressing'     => "VLAN 10: 192.168.10.0/24\nVLAN 20: 192.168.20.0/24 [DEMO]",
                'vlan'              => 'VLAN 10 untuk admin, VLAN 20 untuk user. [DEMO]',
                'routing'           => 'Inter-VLAN routing via router sub-interface. [DEMO]',
                'firewall'          => 'Allow established traffic, limit inter-VLAN access. [DEMO]',
                'nat'               => 'Masquerade traffic dari LAN ke WAN. [DEMO]',
                'monitoring'        => 'Ping gateway dan cek interface counters. [DEMO]',
                'troubleshooting'   => 'Cek trunk/tagged port, gateway client, dan DHCP scope. [DEMO]',
                'configuration'     => "/interface vlan add name=vlan10 vlan-id=10 interface=ether2\n/interface vlan add name=vlan20 vlan-id=20 interface=ether2\n/ip address add address=192.168.10.1/24 interface=vlan10\n/ip address add address=192.168.20.1/24 interface=vlan20\n# DEMO ONLY - replace with real lab config",
                'testing'           => 'Client VLAN 10 dan VLAN 20 dapat gateway masing-masing. [DEMO]',
                'result'            => 'Segmentasi jaringan demo tampil pada halaman lab. [DEMO]',
                'lessons_learned'   => 'Dokumentasi VLAN harus memuat topology, port mapping, dan test result. [DEMO]',
                'thumbnail'         => 'uploads/labs/vlan-segmentation-lab-demo.png',
                'topology_image'    => 'uploads/labs/topologies/vlan-segmentation-lab-demo.png',
                'images'            => [
                    ['image' => 'uploads/labs/images/vlan-segmentation-lab-demo-01.png', 'caption' => 'Demo lab image for VLAN segmentation.'],
                ],
                'status'            => 'published',
                'is_featured'       => 1,
                'is_dummy'          => 1,
                'sort_order'        => 1,
                'published_at'      => $this->now,
            ],
            [
                'category_id'       => $catIds['routing-switching'],
                'title'             => 'MikroTik Basic Routing Lab [DEMO LAB]',
                'slug'              => 'mikrotik-basic-routing-lab-demo',
                'short_description' => '[DEMO LAB] Contoh lab basic routing MikroTik. Data ini placeholder.',
                'objective'         => 'Konfigurasi dasar routing pada perangkat MikroTik.',
                'description'       => '<p><strong>[DEMO LAB - PLACEHOLDER]</strong></p><p>Lab placeholder untuk menguji layout.</p>',
                'topology'          => '[DEMO LAB] ISP - MikroTik - LAN switch - clients.',
                'devices'           => '1 MikroTik router, 1 switch, 2 client PCs. [DEMO]',
                'hardware'          => 'Router board, switch, Ethernet cables. [DEMO]',
                'software'          => 'RouterOS and browser-based admin access. [DEMO]',
                'ip_addressing'     => "WAN: DHCP client\nLAN: 192.168.88.0/24 [DEMO]",
                'routing'           => 'Default route via WAN DHCP. [DEMO]',
                'firewall'          => 'Basic input/drop rules. [DEMO]',
                'nat'               => 'Source NAT masquerade from LAN. [DEMO]',
                'monitoring'        => 'Check interface traffic and ping external host. [DEMO]',
                'troubleshooting'   => 'Verify DHCP lease, NAT rule order, and DNS config. [DEMO]',
                'configuration'     => "/ip dhcp-client add interface=ether1 disabled=no\n/ip address add address=192.168.88.1/24 interface=ether2\n/ip firewall nat add chain=srcnat out-interface=ether1 action=masquerade\n# DEMO ONLY - no production credentials",
                'testing'           => 'Client receives DHCP and can reach internet gateway. [DEMO]',
                'result'            => 'Basic routing lab visual and documentation are ready. [DEMO]',
                'lessons_learned'   => 'Start with addressing, route, DNS, then firewall/NAT validation. [DEMO]',
                'thumbnail'         => 'uploads/labs/mikrotik-basic-routing-lab-demo.png',
                'topology_image'    => 'uploads/labs/topologies/mikrotik-basic-routing-lab-demo.png',
                'images'            => [
                    ['image' => 'uploads/labs/images/mikrotik-basic-routing-lab-demo-01.png', 'caption' => 'Demo lab image for MikroTik routing.'],
                ],
                'status'            => 'published',
                'is_featured'       => 1,
                'is_dummy'          => 1,
                'sort_order'        => 2,
                'published_at'      => $this->now,
            ],
            [
                'category_id'       => $catIds['vpn'],
                'title'             => 'WireGuard VPN Lab [DEMO LAB]',
                'slug'              => 'wireguard-vpn-lab-demo',
                'short_description' => '[DEMO LAB] Contoh lab WireGuard VPN. Data ini placeholder.',
                'objective'         => 'Implementasi WireGuard VPN pada jaringan lokal.',
                'description'       => '<p><strong>[DEMO LAB - PLACEHOLDER]</strong></p><p>Lab placeholder untuk testing.</p>',
                'topology'          => '[DEMO LAB] Remote user connects through WireGuard to LAN services.',
                'devices'           => '1 router, 1 remote client, 1 LAN server. [DEMO]',
                'hardware'          => 'Router and laptop clients. [DEMO]',
                'software'          => 'WireGuard client/server. [DEMO]',
                'ip_addressing'     => "VPN: 10.10.10.0/24\nLAN: 192.168.50.0/24 [DEMO]",
                'routing'           => 'Route VPN subnet to LAN through router. [DEMO]',
                'firewall'          => 'Allow UDP VPN port and restrict LAN access. [DEMO]',
                'vpn'               => 'WireGuard tunnel with placeholder keys intentionally omitted. [DEMO]',
                'monitoring'        => 'Check handshake and allowed IPs. [DEMO]',
                'troubleshooting'   => 'Validate endpoint, firewall, and route return path. [DEMO]',
                'configuration'     => "[Interface]\nAddress = 10.10.10.2/24\n# PrivateKey intentionally omitted\n\n[Peer]\nAllowedIPs = 192.168.50.0/24\n# DEMO ONLY - do not publish real keys",
                'testing'           => 'Remote client can ping allowed internal host. [DEMO]',
                'result'            => 'VPN documentation displays safely without credentials. [DEMO]',
                'lessons_learned'   => 'Never publish private keys, tokens, or real endpoint secrets. [DEMO]',
                'thumbnail'         => 'uploads/labs/wireguard-vpn-lab-demo.png',
                'topology_image'    => 'uploads/labs/topologies/wireguard-vpn-lab-demo.png',
                'images'            => [
                    ['image' => 'uploads/labs/images/wireguard-vpn-lab-demo-01.png', 'caption' => 'Demo lab image for WireGuard VPN.'],
                ],
                'status'            => 'published',
                'is_featured'       => 1,
                'is_dummy'          => 1,
                'sort_order'        => 3,
                'published_at'      => $this->now,
            ],
            [
                'category_id'       => $catIds['monitoring'],
                'title'             => 'Network Monitoring Lab [DEMO LAB]',
                'slug'              => 'network-monitoring-lab-demo',
                'short_description' => '[DEMO LAB] Contoh lab network monitoring. Data ini placeholder.',
                'objective'         => 'Setup monitoring jaringan menggunakan tools open-source.',
                'description'       => '<p><strong>[DEMO LAB - PLACEHOLDER]</strong></p><p>Lab placeholder untuk testing interface monitoring.</p>',
                'topology'          => '[DEMO LAB] Router, switch, AP, server, and monitoring node.',
                'devices'           => 'Router, switch, access point, server, monitoring VM. [DEMO]',
                'hardware'          => 'Network devices and monitoring host. [DEMO]',
                'software'          => 'SNMP collector and dashboard. [DEMO]',
                'ip_addressing'     => 'Management network: 192.168.99.0/24 [DEMO]',
                'vlan'              => 'Optional management VLAN. [DEMO]',
                'routing'           => 'Monitoring node reaches management IPs. [DEMO]',
                'firewall'          => 'Permit SNMP from monitoring node only. [DEMO]',
                'monitoring'        => 'SNMP polling, ping checks, and simple alerting. [DEMO]',
                'troubleshooting'   => 'Check SNMP community, ACL, and device reachability. [DEMO]',
                'configuration'     => "snmpwalk -v2c -c public 192.168.99.1\n# DEMO ONLY - replace community string in real labs",
                'testing'           => 'Dashboard receives status and traffic counters. [DEMO]',
                'result'            => 'Monitoring lab has demo topology, image, and documentation. [DEMO]',
                'lessons_learned'   => 'Monitoring must include access control and clear alert thresholds. [DEMO]',
                'thumbnail'         => 'uploads/labs/network-monitoring-lab-demo.png',
                'topology_image'    => 'uploads/labs/topologies/network-monitoring-lab-demo.png',
                'images'            => [
                    ['image' => 'uploads/labs/images/network-monitoring-lab-demo-01.png', 'caption' => 'Demo lab image for network monitoring.'],
                ],
                'status'            => 'published',
                'is_featured'       => 1,
                'is_dummy'          => 1,
                'sort_order'        => 4,
                'published_at'      => $this->now,
            ],
        ];

        foreach ($labs as $lab) {
            $images = $lab['images'] ?? [];
            unset($lab['images']);

            $db->table('labs')->insert(array_merge($lab, [
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));

            $labId = $db->insertID();
            foreach ($images as $index => $image) {
                $db->table('lab_images')->insert([
                    'lab_id'      => $labId,
                    'image'       => $image['image'],
                    'caption'     => $image['caption'] ?? null,
                    'sort_order'  => $index + 1,
                    'is_featured' => $index === 0 ? 1 : 0,
                    'created_at'  => $this->now,
                ]);
            }
        }
    }

    // ─── ACHIEVEMENTS (DUMMY – no achievement data in data_konten) ───

    private function seedAchievements(): void
    {
        $db = $this->db;
        if ($db->table('achievements')->countAllResults() > 0) {
            return;
        }

        $achievements = [
            [
                'title'        => 'Penyelesaian 90% Gangguan Jaringan Harian',
                'description'  => 'Berhasil menyelesaikan 90% gangguan jaringan dan komputer harian secara terstruktur dan tepat waktu selama masa PKL di PT Adhi Persada Properti.',
                'organization' => 'PT Adhi Persada Properti',
                'date'         => '2024-12-01',
                'sort_order'   => 1,
                'status'       => 'published',
                'is_dummy'     => 0,
            ],
            [
                'title'        => 'Migrasi 500+ Data Aset',
                'description'  => 'Migrasi dan pengelolaan 500+ data aset ke sistem baru, meningkatkan efisiensi hingga 30%.',
                'organization' => 'PT Adhi Persada Properti',
                'date'         => '2024-12-01',
                'sort_order'   => 2,
                'status'       => 'published',
                'is_dummy'     => 0,
            ],
            [
                'title'        => 'Tingkat Kepuasan Pengguna 95%',
                'description'  => 'Koordinasi lintas divisi untuk instalasi dan pemeliharaan perangkat (CCTV, printer, LAN) dengan tingkat kepuasan pengguna 95%.',
                'organization' => 'PT Adhi Persada Properti',
                'date'         => '2024-12-01',
                'sort_order'   => 3,
                'status'       => 'published',
                'is_dummy'     => 0,
            ],
        ];

        foreach ($achievements as $a) {
            $db->table('achievements')->insert(array_merge($a, [
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
        }
    }

    // ─── DOCUMENTS (REAL – CV from data_konten) ──────────────────────

    private function seedDocuments(): void
    {
        $db = $this->db;
        if ($db->table('documents')->countAllResults() > 0) {
            return;
        }

        $db->table('documents')->insert([
            'title'       => 'Curriculum Vitae - Muhammad Azril Achdi',
            'category'    => 'CV',
            'description' => 'CV / Resume Muhammad Azril Achdi - IT Support & Web Developer',
            'file'        => 'uploads/documents/CV_Muhammad_Azril_Achdi.pdf',
            'version'     => '1.0',
            'uploaded_at' => $this->now,
            'visibility'  => 'public',
            'status'      => 'published',
            'is_dummy'    => 0,
            'sort_order'  => 1,
            'created_at'  => $this->now,
            'updated_at'  => $this->now,
        ]);
    }

    // ─── DOCUMENTATION (DUMMY) ───────────────────────────────────────

    private function seedDocumentation(): void
    {
        $db = $this->db;
        if ($db->table('documentation')->countAllResults() > 0) {
            return;
        }

        $docs = [
            [
                'title'        => 'MikroTik Basic Configuration [DEMO DOCUMENTATION]',
                'slug'         => 'mikrotik-basic-configuration-demo',
                'category'     => 'Networking',
                'summary'      => '[DEMO DOCUMENTATION] Panduan konfigurasi dasar MikroTik. Konten ini bersifat placeholder.',
                'excerpt'      => 'Panduan konfigurasi dasar MikroTik RouterOS untuk pemula. [DEMO DOCUMENTATION]',
                'content'      => '<p><strong>[DEMO DOCUMENTATION - PLACEHOLDER]</strong></p><p>Ini adalah dokumentasi contoh untuk menguji tampilan. Konten akan diganti dengan dokumentasi teknis nyata nantinya.</p><h2>Topics yang akan dibahas:</h2><ul><li>Initial setup RouterOS</li><li>IP Address configuration</li><li>DHCP Server setup</li><li>Basic firewall rules</li></ul>',
                'thumbnail'    => 'uploads/documentation/mikrotik-basic-configuration-demo.png',
                'featured_image' => 'uploads/documentation/mikrotik-basic-configuration-demo.png',
                'tags'         => 'DEMO,PLACEHOLDER,Networking',
                'author'       => 'Muhammad Azril Achdi',
                'sort_order'   => 1,
                'is_dummy'     => 1,
            ],
            [
                'title'        => 'VLAN Configuration Guide [DEMO DOCUMENTATION]',
                'slug'         => 'vlan-configuration-guide-demo',
                'category'     => 'Networking',
                'summary'      => '[DEMO DOCUMENTATION] Panduan konfigurasi VLAN. Konten ini bersifat placeholder.',
                'excerpt'      => 'Panduan lengkap konfigurasi VLAN pada managed switch. [DEMO DOCUMENTATION]',
                'content'      => '<p><strong>[DEMO DOCUMENTATION - PLACEHOLDER]</strong></p><p>Dokumentasi placeholder untuk testing layout halaman dokumentasi.</p>',
                'thumbnail'    => 'uploads/documentation/vlan-configuration-guide-demo.png',
                'featured_image' => 'uploads/documentation/vlan-configuration-guide-demo.png',
                'tags'         => 'DEMO,PLACEHOLDER,VLAN',
                'author'       => 'Muhammad Azril Achdi',
                'sort_order'   => 2,
                'is_dummy'     => 1,
            ],
            [
                'title'        => 'Network Monitoring Setup [DEMO DOCUMENTATION]',
                'slug'         => 'network-monitoring-setup-demo',
                'category'     => 'Monitoring',
                'summary'      => '[DEMO DOCUMENTATION] Setup network monitoring. Placeholder.',
                'excerpt'      => 'Panduan setup monitoring jaringan. [DEMO DOCUMENTATION]',
                'content'      => '<p><strong>[DEMO DOCUMENTATION - PLACEHOLDER]</strong></p><p>Dokumentasi placeholder.</p>',
                'thumbnail'    => 'uploads/documentation/network-monitoring-setup-demo.png',
                'featured_image' => 'uploads/documentation/network-monitoring-setup-demo.png',
                'tags'         => 'DEMO,PLACEHOLDER,Monitoring',
                'author'       => 'Muhammad Azril Achdi',
                'sort_order'   => 3,
                'is_dummy'     => 1,
            ],
            [
                'title'        => 'WireGuard VPN Configuration [DEMO DOCUMENTATION]',
                'slug'         => 'wireguard-vpn-configuration-demo',
                'category'     => 'VPN',
                'summary'      => '[DEMO DOCUMENTATION] Konfigurasi WireGuard VPN. Placeholder.',
                'excerpt'      => 'Panduan konfigurasi WireGuard VPN point-to-site. [DEMO DOCUMENTATION]',
                'content'      => '<p><strong>[DEMO DOCUMENTATION - PLACEHOLDER]</strong></p><p>Dokumentasi placeholder untuk testing.</p>',
                'thumbnail'    => 'uploads/documentation/wireguard-vpn-configuration-demo.png',
                'featured_image' => 'uploads/documentation/wireguard-vpn-configuration-demo.png',
                'tags'         => 'DEMO,PLACEHOLDER,VPN',
                'author'       => 'Muhammad Azril Achdi',
                'sort_order'   => 4,
                'is_dummy'     => 1,
            ],
        ];

        foreach ($docs as $doc) {
            $db->table('documentation')->insert(array_merge($doc, [
                'status'       => 'published',
                'published_at' => $this->now,
                'created_at'   => $this->now,
                'updated_at'   => $this->now,
            ]));
        }
    }

    // ─── BLOG (DUMMY) ────────────────────────────────────────────────

    private function seedBlog(): void
    {
        $db = $this->db;
        if ($db->table('blog_posts')->countAllResults() > 0) {
            return;
        }

        // Categories
        $categories = [
            ['name' => 'Networking',      'slug' => 'networking',      'sort_order' => 1, 'is_dummy' => 0],
            ['name' => 'Web Development', 'slug' => 'web-development', 'sort_order' => 2, 'is_dummy' => 0],
            ['name' => 'IT Tips',         'slug' => 'it-tips',         'sort_order' => 3, 'is_dummy' => 0],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $db->table('blog_categories')->insert(array_merge($cat, [
                'status'     => 'active',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
            $catIds[$cat['slug']] = $db->insertID();
        }

        // Tags
        $tags = [
            ['name' => 'MikroTik',    'slug' => 'mikrotik',    'is_dummy' => 1],
            ['name' => 'VLAN',        'slug' => 'vlan',        'is_dummy' => 1],
            ['name' => 'VPN',         'slug' => 'vpn',         'is_dummy' => 1],
            ['name' => 'Monitoring',  'slug' => 'monitoring',  'is_dummy' => 1],
        ];

        $tagIds = [];
        foreach ($tags as $tag) {
            $db->table('blog_tags')->insert(array_merge($tag, [
                'status'     => 'active',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
            $tagIds[$tag['slug']] = $db->insertID();
        }

        $posts = [
            [
                'category_id' => $catIds['networking'],
                'title'       => 'Getting Started with MikroTik Networking [PLACEHOLDER CONTENT]',
                'slug'        => 'getting-started-mikrotik-networking-placeholder',
                'excerpt'     => '[PLACEHOLDER] Artikel contoh tentang pengenalan MikroTik networking. Konten ini bersifat sementara dan akan diganti.',
                'content'     => '<p><strong>[PLACEHOLDER CONTENT - DUMMY ARTICLE]</strong></p><p>Ini adalah artikel contoh (dummy) untuk menguji layout blog di portfolio. Konten ini <strong>bukan</strong> artikel asli dari pemilik portfolio.</p><p>Artikel nyata akan ditambahkan melalui panel admin.</p>',
                'thumbnail'   => 'uploads/blog/getting-started-mikrotik-networking-placeholder.png',
                'featured_image' => 'uploads/blog/getting-started-mikrotik-networking-placeholder.png',
                'author'      => 'Muhammad Azril Achdi',
                'sort_order'  => 1,
                'is_dummy'    => 1,
                'tags'        => [$tagIds['mikrotik']],
            ],
            [
                'category_id' => $catIds['networking'],
                'title'       => 'Introduction to VLAN [PLACEHOLDER CONTENT]',
                'slug'        => 'introduction-to-vlan-placeholder',
                'excerpt'     => '[PLACEHOLDER] Artikel contoh tentang VLAN. Data dummy untuk testing layout.',
                'content'     => '<p><strong>[PLACEHOLDER CONTENT - DUMMY ARTICLE]</strong></p><p>Artikel placeholder untuk testing. Bukan konten nyata.</p>',
                'thumbnail'   => 'uploads/blog/introduction-to-vlan-placeholder.png',
                'featured_image' => 'uploads/blog/introduction-to-vlan-placeholder.png',
                'author'      => 'Muhammad Azril Achdi',
                'sort_order'  => 2,
                'is_dummy'    => 1,
                'tags'        => [$tagIds['vlan']],
            ],
            [
                'category_id' => $catIds['networking'],
                'title'       => 'Understanding WireGuard VPN [PLACEHOLDER CONTENT]',
                'slug'        => 'understanding-wireguard-vpn-placeholder',
                'excerpt'     => '[PLACEHOLDER] Artikel contoh tentang WireGuard VPN. Dummy data.',
                'content'     => '<p><strong>[PLACEHOLDER CONTENT - DUMMY ARTICLE]</strong></p><p>Artikel placeholder.</p>',
                'thumbnail'   => 'uploads/blog/understanding-wireguard-vpn-placeholder.png',
                'featured_image' => 'uploads/blog/understanding-wireguard-vpn-placeholder.png',
                'author'      => 'Muhammad Azril Achdi',
                'sort_order'  => 3,
                'is_dummy'    => 1,
                'tags'        => [$tagIds['vpn']],
            ],
        ];

        foreach ($posts as $post) {
            $postTags = $post['tags'] ?? [];
            unset($post['tags']);

            $db->table('blog_posts')->insert(array_merge($post, [
                'status'       => 'published',
                'published_at' => $this->now,
                'created_at'   => $this->now,
                'updated_at'   => $this->now,
            ]));

            $postId = $db->insertID();
            foreach ($postTags as $tagId) {
                $db->table('blog_post_tags')->insert([
                    'blog_post_id' => $postId,
                    'blog_tag_id'  => $tagId,
                    'created_at'   => $this->now,
                ]);
            }
        }
    }

    // ─── GALLERY (DUMMY – no gallery images in data_konten) ──────────

    private function seedGallery(): void
    {
        $db = $this->db;
        if ($db->table('gallery')->countAllResults() > 0) {
            return;
        }

        // Use certificate images as gallery items for now
        $categories = [
            ['name' => 'Sertifikat',    'slug' => 'sertifikat',    'sort_order' => 1, 'is_dummy' => 0],
            ['name' => 'Kegiatan',      'slug' => 'kegiatan',      'sort_order' => 2, 'is_dummy' => 1],
            ['name' => 'Demo Assets',   'slug' => 'demo-assets',   'sort_order' => 99, 'is_dummy' => 1],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $db->table('gallery_categories')->insert(array_merge($cat, [
                'status'     => 'active',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
            $catIds[$cat['slug']] = $db->insertID();
        }

        // Real gallery entries from certificate images
        $items = [
            [
                'category_id' => $catIds['sertifikat'],
                'title'       => 'Sertifikat CCNA 200-301 - IDN.ID',
                'description' => 'Sertifikat Cisco CCNA 200-301 dari IDN.ID Training Center',
                'image'       => 'uploads/certificates/ccna-200-301-idn.jpg',
                'date'        => '2026-09-03',
                'sort_order'  => 1,
                'status'      => 'published',
                'is_dummy'    => 0,
            ],
            [
                'category_id' => $catIds['sertifikat'],
                'title'       => 'Sertifikat Prakerin - PT Adhi Persada Properti',
                'description' => 'Sertifikat Praktik Kerja Industri dari PT Adhi Persada Properti',
                'image'       => 'uploads/certificates/prakerin-adhi-persada.jpg',
                'date'        => '2024-12-31',
                'sort_order'  => 2,
                'status'      => 'published',
                'is_dummy'    => 0,
            ],
            [
                'category_id' => $catIds['sertifikat'],
                'title'       => 'English Placement Test - UAD',
                'description' => 'Sertifikat English Placement Test dari Universitas Ahmad Dahlan',
                'image'       => 'uploads/certificates/toefl-uad-2025.jpg',
                'date'        => '2025-10-09',
                'sort_order'  => 3,
                'status'      => 'published',
                'is_dummy'    => 0,
            ],
            [
                'category_id' => $catIds['demo-assets'],
                'title'       => 'Networking Lab Workbench [DEMO]',
                'description' => 'Demo photo asset for networking lab visual preview. Replace with real uploaded documentation.',
                'image'       => 'uploads/labs/network-lab-workbench-demo.png',
                'date'        => date('Y-m-d'),
                'sort_order'  => 50,
                'status'      => 'published',
                'is_dummy'    => 1,
            ],
            [
                'category_id' => $catIds['demo-assets'],
                'title'       => 'Developer Workspace [DEMO]',
                'description' => 'Demo photo asset for project and blog preview. Replace with real uploaded workspace image.',
                'image'       => 'uploads/projects/developer-workspace-demo.png',
                'date'        => date('Y-m-d'),
                'sort_order'  => 51,
                'status'      => 'published',
                'is_dummy'    => 1,
            ],
        ];

        foreach ($items as $item) {
            $db->table('gallery')->insert(array_merge($item, [
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
        }
    }

    // ─── SITE SETTINGS ───────────────────────────────────────────────

    private function seedSiteSettings(): void
    {
        $db = $this->db;
        if ($db->table('site_settings')->countAllResults() > 0) {
            return;
        }

        $settings = [
            ['setting_key' => 'site_title',       'setting_value' => 'Muhammad Azril Achdi - IT Support & Web Developer', 'type' => 'text',     'setting_group' => 'general'],
            ['setting_key' => 'site_description',  'setting_value' => 'Portfolio website Muhammad Azril Achdi - Mahasiswa S1 Informatika dengan pengalaman sebagai IT Support dan Web Developer.', 'type' => 'textarea', 'setting_group' => 'general'],
            ['setting_key' => 'seo_title',         'setting_value' => 'Muhammad Azril Achdi | IT Support & Web Developer Portfolio', 'type' => 'text', 'setting_group' => 'seo'],
            ['setting_key' => 'seo_description',   'setting_value' => 'Portfolio Muhammad Azril Achdi - IT Support & Web Developer. Mahasiswa S1 Informatika UAD, berpengalaman di jaringan, server, dan web development.', 'type' => 'textarea', 'setting_group' => 'seo'],
            ['setting_key' => 'seo_keywords',      'setting_value' => 'Muhammad Azril Achdi, IT Support, Web Developer, Portfolio, Informatika, Jaringan, PHP, CodeIgniter', 'type' => 'text', 'setting_group' => 'seo'],
            ['setting_key' => 'default_og_image_url', 'setting_value' => 'uploads/projects/portfolio-website-placeholder.png', 'type' => 'file', 'setting_group' => 'seo'],
            ['setting_key' => 'contact_email',     'setting_value' => 'muhammad.azril.achdi@gmail.com', 'type' => 'text', 'setting_group' => 'contact'],
            ['setting_key' => 'contact_phone',     'setting_value' => '081282150702',                   'type' => 'text', 'setting_group' => 'contact'],
            ['setting_key' => 'contact_location',  'setting_value' => 'Perumahan Bekasi Timur Permai, Tambun Selatan', 'type' => 'text', 'setting_group' => 'contact'],
        ];

        foreach ($settings as $s) {
            $db->table('site_settings')->insert(array_merge($s, [
                'is_public'  => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]));
        }
    }
}
