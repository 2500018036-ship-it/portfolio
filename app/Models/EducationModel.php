<?php

namespace App\Models;

use CodeIgniter\Model;

class EducationModel extends Model
{
    protected $table           = 'education';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['institution', 'program', 'degree', 'major', 'start_date', 'end_date', 'is_current', 'description', 'sort_order', 'status', 'is_dummy'];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'institution' => 'required|max_length[180]',
        'program'     => 'permit_empty|max_length[180]',
        'status'      => 'required|max_length[30]',
    ];
}
