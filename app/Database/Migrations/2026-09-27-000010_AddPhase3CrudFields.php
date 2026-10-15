<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhase3CrudFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('profiles', [
            'availability' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'career_focus',
            ],
            'github_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'resume_file',
            ],
            'linkedin_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'github_url',
            ],
            'website_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'linkedin_url',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('social_links', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('skill_categories', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('skills', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('project_categories', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('projects', [
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'documentation_url',
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'start_date',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
                'after'      => 'is_featured',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('experiences', [
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 160,
                'null'       => true,
                'after'      => 'position',
            ],
            'is_current' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'end_date',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
                'after'      => 'technologies',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('education', [
            'program' => [
                'type'       => 'VARCHAR',
                'constraint' => 180,
                'null'       => true,
                'after'      => 'institution',
            ],
            'is_current' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'end_date',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
                'after'      => 'description',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('achievements', [
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
                'after'      => 'document',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('achievements', ['sort_order', 'deleted_at']);
        $this->forge->dropColumn('education', ['program', 'is_current', 'sort_order', 'deleted_at']);
        $this->forge->dropColumn('experiences', ['location', 'is_current', 'sort_order', 'deleted_at']);
        $this->forge->dropColumn('projects', ['start_date', 'end_date', 'sort_order', 'deleted_at']);
        $this->forge->dropColumn('project_categories', 'deleted_at');
        $this->forge->dropColumn('skills', 'deleted_at');
        $this->forge->dropColumn('skill_categories', 'deleted_at');
        $this->forge->dropColumn('social_links', 'deleted_at');
        $this->forge->dropColumn('profiles', ['availability', 'github_url', 'linkedin_url', 'website_url', 'deleted_at']);
    }
}
