<?php

namespace App\Models;

use CodeIgniter\Model;

class SkillModel extends Model
{
    protected $table           = 'skills';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['category_id', 'name', 'description', 'icon', 'level', 'sort_order', 'status', 'is_dummy'];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'name'   => 'required|max_length[120]',
        'level'  => 'permit_empty|max_length[50]',
        'status' => 'required|max_length[30]',
    ];
}
