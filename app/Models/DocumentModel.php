<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table           = 'documents';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'title',
        'category',
        'description',
        'file',
        'version',
        'uploaded_at',
        'visibility',
        'status',
        'sort_order',
        'is_dummy',
    ];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'title'      => 'required|max_length[180]',
        'file'       => 'required|max_length[255]',
        'visibility' => 'required|in_list[public,private]',
        'status'     => 'required|max_length[30]',
    ];
}
