<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabBlocks extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('lab_blocks')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lab_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 160,
            ],
            'block_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'text',
            ],
            'content' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_original_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('lab_id');
        $this->forge->addKey('block_type');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('lab_id', 'labs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lab_blocks', true);
    }

    public function down()
    {
        $this->forge->dropTable('lab_blocks', true);
    }
}
