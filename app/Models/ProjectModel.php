<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table           = 'projects';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'problem',
        'solution',
        'features',
        'technologies',
        'architecture',
        'challenges',
        'result',
        'lessons_learned',
        'thumbnail',
        'github_url',
        'demo_url',
        'documentation_url',
        'start_date',
        'end_date',
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
        'slug'   => 'required|max_length[200]|is_unique[projects.slug,id,{id}]',
        'github_url' => 'permit_empty|valid_url|max_length[255]',
        'demo_url' => 'permit_empty|valid_url|max_length[255]',
        'documentation_url' => 'permit_empty|valid_url|max_length[255]',
        'status' => 'required|max_length[30]',
    ];
}
