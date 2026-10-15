<?php

namespace App\Models;

use CodeIgniter\Model;

class LabModel extends Model
{
    protected $table           = 'labs';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'objective',
        'description',
        'topology',
        'devices',
        'hardware',
        'software',
        'ip_addressing',
        'vlan',
        'routing',
        'firewall',
        'nat',
        'vpn',
        'monitoring',
        'troubleshooting',
        'configuration',
        'testing',
        'result',
        'lessons_learned',
        'thumbnail',
        'topology_image',
        'documentation_url',
        'status',
        'is_featured',
        'is_dummy',
        'sort_order',
        'published_at',
    ];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'title'  => 'required|max_length[180]',
        'slug'   => 'required|max_length[200]|is_unique[labs.slug,id,{id}]',
        'documentation_url' => 'permit_empty|valid_url|max_length[255]',
        'status' => 'required|max_length[30]',
    ];
}
