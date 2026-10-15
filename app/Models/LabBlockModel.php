<?php

namespace App\Models;

use CodeIgniter\Model;

class LabBlockModel extends Model
{
    protected $table           = 'lab_blocks';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'lab_id',
        'title',
        'block_type',
        'content',
        'file_path',
        'file_original_name',
        'sort_order',
        'status',
    ];
    protected $useTimestamps   = true;
    protected $validationRules = [
        'lab_id'     => 'required|is_natural_no_zero',
        'title'      => 'required|max_length[160]',
        'block_type' => 'required|in_list[text,code,image,file]',
        'status'     => 'required|in_list[active,inactive]',
    ];
}
