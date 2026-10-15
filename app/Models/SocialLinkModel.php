<?php

namespace App\Models;

use CodeIgniter\Model;

class SocialLinkModel extends Model
{
    protected $table           = 'social_links';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['platform', 'label', 'url', 'icon', 'sort_order', 'status', 'is_dummy'];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'platform' => 'required|max_length[60]',
        'url'      => 'required|valid_url|max_length[255]',
        'status'   => 'required|max_length[30]',
    ];
}
