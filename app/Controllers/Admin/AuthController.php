<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('is_logged_in') && session()->get('role') === 'admin') {
            return redirect()->to(site_url('admin'));
        }

        return view('admin/auth/login', [
            'title' => 'Admin Login',
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)
            ->where('status', 'active')
            ->first();

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        if ($user['role'] !== 'admin') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id'      => $user['id'],
            'email'        => $user['email'],
            'name'         => $user['username'],
            'role'         => $user['role'],
            'is_logged_in' => true,
        ]);

        return redirect()
            ->to(site_url('admin'))
            ->with('success', 'Login berhasil.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('admin/login?logged_out=1'));
    }
}
