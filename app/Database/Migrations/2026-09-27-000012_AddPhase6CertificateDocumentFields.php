<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhase6CertificateDocumentFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('certificate_categories', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->forge->addColumn('certificates', [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
                'after'      => 'title',
            ],
            'expiration_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'issue_date',
            ],
            'skills_related' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'description',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
                'after'      => 'verification_url',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);

        $this->db->query('ALTER TABLE certificates ADD UNIQUE KEY certificates_slug_unique (slug)');

        $this->forge->addColumn('documents', [
            'version' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'file',
            ],
            'uploaded_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'version',
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
    }

    public function down()
    {
        $this->db->query('ALTER TABLE certificates DROP INDEX certificates_slug_unique');
        $this->forge->dropColumn('documents', ['version', 'uploaded_at', 'sort_order', 'deleted_at']);
        $this->forge->dropColumn('certificates', ['slug', 'expiration_date', 'skills_related', 'sort_order', 'deleted_at']);
        $this->forge->dropColumn('certificate_categories', 'deleted_at');
    }
}
