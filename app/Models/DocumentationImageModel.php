<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentationImageModel extends Model
{
    protected $table           = 'documentation_images';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['documentation_id', 'image', 'caption', 'sort_order', 'created_at'];
    protected $useTimestamps   = false;
    protected $validationRules = [
        'documentation_id' => 'required|is_natural_no_zero',
        'image'            => 'required|max_length[255]',
    ];
}
