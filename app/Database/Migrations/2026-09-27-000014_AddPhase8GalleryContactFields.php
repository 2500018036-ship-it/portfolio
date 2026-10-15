<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhase8GalleryContactFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('gallery_categories', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('gallery', [
            'date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'description',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
                'after'      => 'status',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('contact_messages', [
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
                'after'      => 'message',
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'ip_address',
            ],
            'read_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status',
            ],
            'archived_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'read_at',
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
        $this->forge->dropColumn('contact_messages', ['ip_address', 'user_agent', 'read_at', 'archived_at', 'deleted_at']);
        $this->forge->dropColumn('gallery', ['date', 'sort_order', 'deleted_at']);
        $this->forge->dropColumn('gallery_categories', 'deleted_at');
    }
}
