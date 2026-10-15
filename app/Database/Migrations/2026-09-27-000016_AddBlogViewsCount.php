<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBlogViewsCount extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('blog_posts') && ! $this->db->fieldExists('views_count', 'blog_posts')) {
            $this->forge->addColumn('blog_posts', [
                'views_count' => [
                    'type'       => 'INT',
                    'constraint' => 10,
                    'unsigned'   => true,
                    'default'    => 0,
                    'after'      => 'sort_order',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('blog_posts') && $this->db->fieldExists('views_count', 'blog_posts')) {
            $this->forge->dropColumn('blog_posts', 'views_count');
        }
    }
}
