<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhase7DocumentationBlogFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('documentation', [
            'summary' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'category',
            ],
            'featured_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'thumbnail',
            ],
            'tags' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'featured_image',
            ],
            'author' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'tags',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
                'after'      => 'published_at',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('blog_categories', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('blog_posts', [
            'featured_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'thumbnail',
            ],
            'author' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'featured_image',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
                'after'      => 'published_at',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('blog_tags', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('blog_tags', 'deleted_at');
        $this->forge->dropColumn('blog_posts', ['featured_image', 'author', 'sort_order', 'deleted_at']);
        $this->forge->dropColumn('blog_categories', 'deleted_at');
        $this->forge->dropColumn('documentation', ['summary', 'featured_image', 'tags', 'author', 'sort_order', 'deleted_at']);
    }
}
