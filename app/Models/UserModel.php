<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['username', 'email', 'password_hash', 'role', 'status'];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'username'      => 'required|max_length[50]|is_unique[users.username,id,{id}]',
        'email'         => 'required|valid_email|max_length[120]|is_unique[users.email,id,{id}]',
        'password_hash' => 'required|max_length[255]',
        'role'          => 'required|max_length[30]',
        'status'        => 'required|max_length[30]',
    ];
}
