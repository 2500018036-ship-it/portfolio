<?php

namespace App\Models;

use CodeIgniter\Model;

class LabImageModel extends Model
{
    protected $table           = 'lab_images';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['lab_id', 'image', 'caption', 'sort_order', 'is_featured', 'created_at'];
    protected $useTimestamps   = false;
    protected $validationRules = [
        'lab_id' => 'required|is_natural_no_zero',
        'image'  => 'required|max_length[255]',
    ];
}
