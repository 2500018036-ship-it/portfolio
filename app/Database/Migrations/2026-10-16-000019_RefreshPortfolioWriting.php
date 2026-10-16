<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefreshPortfolioWriting extends Migration
{
    public function up()
    {
        // Keep topology in the Home Lab section; retain the original gallery record.
        $this->db->table('gallery')->where('title', 'Topologi Home Lab Pribadi')
            ->update(['status' => 'draft', 'updated_at' => date('Y-m-d H:i:s')]);

        \Config\Database::seeder()->call('VerifiedPortfolioSeeder');
    }

    public function down()
    {
        // Content and owner uploads remain intact; data recovery uses the deploy backup.
    }
}
