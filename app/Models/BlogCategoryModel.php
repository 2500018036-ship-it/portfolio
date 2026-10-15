<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogCategoryModel extends Model
{
    protected $table           = 'blog_categories';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['name', 'slug', 'description', 'sort_order', 'status', 'is_dummy'];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'name'   => 'required|max_length[100]',
        'slug'   => 'required|max_length[120]|is_unique[blog_categories.slug,id,{id}]',
        'status' => 'required|max_length[30]',
    ];
}
