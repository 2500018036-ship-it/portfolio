<?php

namespace App\Models;

use CodeIgniter\Model;

class SkillCategoryModel extends Model
{
    protected $table           = 'skill_categories';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['name', 'slug', 'description', 'sort_order', 'status', 'is_dummy'];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'name'   => 'required|max_length[100]',
        'slug'   => 'required|max_length[120]|is_unique[skill_categories.slug,id,{id}]',
        'status' => 'required|max_length[30]',
    ];
}
