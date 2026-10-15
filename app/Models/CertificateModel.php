<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table           = 'certificates';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = [
        'category_id',
        'title',
        'slug',
        'issuer',
        'credential_id',
        'description',
        'skills_related',
        'issue_date',
        'expiration_date',
        'certificate_image',
        'certificate_file',
        'verification_url',
        'sort_order',
        'status',
        'is_dummy',
    ];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'title'            => 'required|max_length[180]',
        'slug'             => 'required|max_length[200]|is_unique[certificates.slug,id,{id}]',
        'issuer'           => 'permit_empty|max_length[160]',
        'verification_url' => 'permit_empty|valid_url|max_length[255]',
        'status'           => 'required|max_length[30]',
    ];
}
