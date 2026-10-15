<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table           = 'profiles';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'name',
        'professional_title',
        'headline',
        'short_bio',
        'full_bio',
        'profile_image',
        'location',
        'email',
        'phone',
        'career_focus',
        'availability',
        'resume_file',
        'github_url',
        'linkedin_url',
        'website_url',
        'status',
        'is_dummy',
    ];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'name'         => 'required|max_length[120]',
        'email'        => 'permit_empty|valid_email|max_length[120]',
        'github_url'   => 'permit_empty|valid_url|max_length[255]',
        'linkedin_url' => 'permit_empty|valid_url|max_length[255]',
        'website_url'  => 'permit_empty|valid_url|max_length[255]',
        'status'       => 'required|max_length[30]',
    ];
}
