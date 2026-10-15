<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactMessageModel extends Model
{
    protected $table           = 'contact_messages';
    protected $primaryKey      = 'id';
    protected $returnType      = 'array';
    protected $allowedFields   = ['name', 'email', 'subject', 'message', 'ip_address', 'user_agent', 'status', 'read_at', 'archived_at'];
    protected $useTimestamps   = true;
    protected $useSoftDeletes  = true;
    protected $validationRules = [
        'name'    => 'required|max_length[120]',
        'email'   => 'required|valid_email|max_length[120]',
        'subject' => 'permit_empty|max_length[180]',
        'message' => 'required|max_length[5000]',
        'status'  => 'required|in_list[unread,read,archived]',
    ];
}
