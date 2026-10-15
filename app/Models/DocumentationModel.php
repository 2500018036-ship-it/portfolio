<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentationModel extends Model
{
    protected $table           = 'documentation';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'title',
        'slug',
        'category',
        'summary',
        'excerpt',
        'content',
        'thumbnail',
        'featured_image',
        'tags',
        'author',
        'status',
        'published_at',
        'sort_order',
        'is_dummy',
    ];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'title'  => 'required|max_length[180]',
        'slug'   => 'required|max_length[200]|is_unique[documentation.slug,id,{id}]',
        'status' => 'required|max_length[30]',
    ];
}
