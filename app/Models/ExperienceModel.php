<?php

namespace App\Models;

use CodeIgniter\Model;

class ExperienceModel extends Model
{
    protected $table           = 'experiences';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'company',
        'position',
        'location',
        'employment_type',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'responsibilities',
        'achievements',
        'technologies',
        'sort_order',
        'status',
        'is_dummy',
    ];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'company'  => 'required|max_length[160]',
        'position' => 'required|max_length[160]',
        'status'   => 'required|max_length[30]',
    ];
}
