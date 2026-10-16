<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PublishVerifiedPortfolio extends Migration
{
    public function up()
    {
        \Config\Database::seeder()->call('PortfolioContentSeeder');
    }

    public function down()
    {
        // Content edits are retained. Restore the pre-deploy backup for a data rollback.
    }
}
