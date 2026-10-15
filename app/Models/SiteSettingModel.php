<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingModel extends Model
{
    protected $table           = 'site_settings';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['setting_key', 'setting_value', 'type', 'setting_group', 'is_public'];
    protected $useTimestamps   = true;
    protected $validationRules = [
        'setting_key'   => 'required|max_length[100]|is_unique[site_settings.setting_key,id,{id}]',
        'type'          => 'required|max_length[30]',
        'setting_group' => 'required|max_length[60]',
    ];
}
