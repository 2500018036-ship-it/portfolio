<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $username = env('ADMIN_USERNAME') ?: 'admin';
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            throw new RuntimeException('Set ADMIN_EMAIL and ADMIN_PASSWORD in .env before running AdminUserSeeder.');
        }

        $userModel = new UserModel();

        if ($userModel->where('email', $email)->first()) {
            return;
        }

        $userModel->insert([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'admin',
            'status'        => 'active',
        ]);
    }
}
