<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogPostModel extends Model
{
    protected $table           = 'blog_posts';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'featured_image',
        'author',
        'status',
        'published_at',
        'sort_order',
        'views_count',
        'is_dummy',
    ];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'title'  => 'required|max_length[180]',
        'slug'   => 'required|max_length[200]|is_unique[blog_posts.slug,id,{id}]',
        'status' => 'required|max_length[30]',
    ];
}
