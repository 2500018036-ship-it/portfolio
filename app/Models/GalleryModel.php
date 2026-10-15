<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryModel extends Model
{
    protected $table           = 'gallery';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['category_id', 'title', 'description', 'date', 'image', 'status', 'sort_order', 'is_dummy'];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'title'  => 'required|max_length[180]',
        'image'  => 'required|max_length[255]',
        'status' => 'required|max_length[30]',
    ];
}
