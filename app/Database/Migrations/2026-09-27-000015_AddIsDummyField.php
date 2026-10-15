<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsDummyField extends Migration
{
    /**
     * Tables that receive the is_dummy column.
     * Every content table that may hold placeholder data is listed here.
     */
    private array $tables = [
        'profiles',
        'social_links',
        'skill_categories',
        'skills',
        'project_categories',
        'projects',
        'lab_categories',
        'labs',
        'certificate_categories',
        'certificates',
        'experiences',
        'education',
        'achievements',
        'documents',
        'documentation',
        'blog_categories',
        'blog_posts',
        'blog_tags',
        'gallery_categories',
        'gallery',
    ];

    public function up()
    {
        $fieldDef = [
            'is_dummy' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'status',
            ],
        ];

        foreach ($this->tables as $table) {
            if ($this->db->tableExists($table) && ! $this->db->fieldExists('is_dummy', $table)) {
                $this->forge->addColumn($table, $fieldDef);
            }
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            if ($this->db->tableExists($table) && $this->db->fieldExists('is_dummy', $table)) {
                $this->forge->dropColumn($table, 'is_dummy');
            }
        }
    }
}
