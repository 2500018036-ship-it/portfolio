<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateCategoryModel extends Model
{
    protected $table           = 'certificate_categories';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['name', 'slug', 'description', 'sort_order', 'status', 'is_dummy'];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'name'   => 'required|max_length[100]',
        'slug'   => 'required|max_length[120]|is_unique[certificate_categories.slug,id,{id}]',
        'status' => 'required|max_length[30]',
    ];
}
