<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhase5ProjectLabFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('projects', [
            'challenges' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'architecture',
            ],
            'result' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'challenges',
            ],
            'lessons_learned' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'result',
            ],
        ]);

        $this->forge->addColumn('project_images', [
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'sort_order',
            ],
        ]);

        $this->forge->addColumn('lab_categories', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('labs', [
            'devices' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'topology',
            ],
            'vlan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'ip_addressing',
            ],
            'routing' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'vlan',
            ],
            'firewall' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'routing',
            ],
            'nat' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'firewall',
            ],
            'vpn' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'nat',
            ],
            'monitoring' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'vpn',
            ],
            'troubleshooting' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'monitoring',
            ],
            'lessons_learned' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'result',
            ],
            'topology_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'thumbnail',
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

        $this->forge->addColumn('lab_images', [
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'sort_order',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('lab_images', 'is_featured');
        $this->forge->dropColumn('labs', [
            'devices',
            'vlan',
            'routing',
            'firewall',
            'nat',
            'vpn',
            'monitoring',
            'troubleshooting',
            'lessons_learned',
            'topology_image',
            'sort_order',
            'deleted_at',
        ]);
        $this->forge->dropColumn('lab_categories', 'deleted_at');
        $this->forge->dropColumn('project_images', 'is_featured');
        $this->forge->dropColumn('projects', ['challenges', 'result', 'lessons_learned']);
    }
}
