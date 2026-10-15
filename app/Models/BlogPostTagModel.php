<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogPostTagModel extends Model
{
    protected $table           = 'blog_post_tags';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['blog_post_id', 'blog_tag_id', 'created_at'];
    protected $useTimestamps   = false;
    protected $validationRules = [
        'blog_post_id' => 'required|is_natural_no_zero',
        'blog_tag_id'  => 'required|is_natural_no_zero',
    ];
}
