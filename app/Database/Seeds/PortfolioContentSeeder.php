<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Initial content from the supplied CV/certificates and the reviewed content bundle. */
class PortfolioContentSeeder extends Seeder
{
    private string $now;

    public function run()
    {
        $this->now = date('Y-m-d H:i:s');
        $this->seedSkills();
        $this->seedExperience();
        $this->seedEducation();
        $this->seedCertificates();
        $this->seedDocuments();
        $this->call('VerifiedPortfolioSeeder');
    }

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
            ['category' => 'jaringan-infrastruktur', 'name' => 'Jaringan Komputer Dasar (IP, LAN, WiFi)',        'level' => null, 'sort_order' => 1],
            ['category' => 'jaringan-infrastruktur', 'name' => 'Troubleshooting Koneksi',                       'level' => null, 'sort_order' => 2],
            ['category' => 'jaringan-infrastruktur', 'name' => 'Server & Perangkat Jaringan (Rack Mounting, Cabling)', 'level' => null, 'sort_order' => 3],
            ['category' => 'jaringan-infrastruktur', 'name' => 'Hardware Support (PC, Printer, CCTV)',           'level' => null, 'sort_order' => 4],
            ['category' => 'jaringan-infrastruktur', 'name' => 'Instalasi & Konfigurasi Windows/Office',        'level' => null, 'sort_order' => 5],
            // Web Development
            ['category' => 'web-development', 'name' => 'PHP (Native, CodeIgniter 3 & 4, Laravel)',            'level' => null, 'sort_order' => 1],
            ['category' => 'web-development', 'name' => 'MySQL (Database Management)',                         'level' => null, 'sort_order' => 2],
            // Tools & Software
            ['category' => 'tools-software', 'name' => 'Git (Version Control)',                                 'level' => null, 'sort_order' => 1],
            ['category' => 'tools-software', 'name' => 'Dokumentasi Teknis',                                    'level' => null, 'sort_order' => 2],
            // Soft Skills
            ['category' => 'soft-skills', 'name' => 'Problem Solving',                                          'level' => null, 'sort_order' => 1],
            ['category' => 'soft-skills', 'name' => 'Bahasa Inggris (Lisan & Tulisan)',                         'level' => null, 'sort_order' => 2],
            ['category' => 'soft-skills', 'name' => 'Komunikasi Efektif & Kerja Tim',                          'level' => null, 'sort_order' => 3],
            ['category' => 'soft-skills', 'name' => 'Manajemen Waktu & Organisasi',                            'level' => null, 'sort_order' => 4],
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
            'location'        => null,
            'start_date'      => '2024-07-22',
            'end_date'        => '2024-12-20',
            'is_current'      => 0,
            'description'     => 'PKL/Magang di PT Adhi Persada Properti (A Subsidiary of PT Adhi Karya (Persero) Tbk.). Berperan sebagai Web Development Intern dan IT Support.',
            'responsibilities'=> 'Instalasi dan troubleshooting jaringan serta perangkat, penataan server, dokumentasi teknis, dan pengembangan PHP/MySQL.',
            'achievements'    => null,
            'technologies'    => 'PHP, MySQL, LAN, WiFi, Windows, CCTV, Printer',
            'sort_order'      => 1,
            'status'          => 'published',
            'is_dummy'        => 0,
            'created_at'      => $this->now,
            'updated_at'      => $this->now,
        ]);
    }

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
                'title'             => 'Pelatihan Cisco CCNA 200-301 — IDN.ID',
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
}
