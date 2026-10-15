<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectImageModel extends Model
{
    protected $table           = 'project_images';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['project_id', 'image', 'caption', 'sort_order', 'is_featured', 'created_at'];
    protected $useTimestamps   = false;
    protected $validationRules = [
        'project_id' => 'required|is_natural_no_zero',
        'image'      => 'required|max_length[255]',
    ];
}
