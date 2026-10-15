<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabs extends Migration
{
    public function up()
    {
        $this->createLabCategoriesTable();
        $this->createLabsTable();
        $this->createLabImagesTable();
    }

    public function down()
    {
        $this->forge->dropTable('lab_images', true);
        $this->forge->dropTable('labs', true);
        $this->forge->dropTable('lab_categories', true);
    }

    private function createLabCategoriesTable(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('slug', false, true);
        $this->forge->addKey('status');
        $this->forge->createTable('lab_categories', true);
    }

    private function createLabsTable(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 180,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'short_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'objective' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'description' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'topology' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'hardware' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'software' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ip_addressing' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'configuration' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'testing' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'result' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'thumbnail' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'documentation_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'draft',
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'published_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey('category_id');
        $this->forge->addKey('slug', false, true);
        $this->forge->addKey('status');
        $this->forge->addKey('is_featured');
        $this->forge->addForeignKey('category_id', 'lab_categories', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('labs', true);
    }

    private function createLabImagesTable(): void
    {
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
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'caption' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('lab_id');
        $this->forge->addForeignKey('lab_id', 'labs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lab_images', true);
    }
}
