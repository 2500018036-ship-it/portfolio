<?php

namespace App\Controllers\Admin;

use App\Models\SiteSettingModel;
use App\Models\UserModel;

class SettingsController extends AdminController
{
    private const SITE_SETTINGS = [
        'site_title' => [
            'label'     => 'Site Title',
            'input'     => 'text',
            'type'      => 'text',
            'group'     => 'general',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[160]',
            'help'      => 'Judul utama website portfolio.',
        ],
        'site_description' => [
            'label'     => 'Site Description',
            'input'     => 'textarea',
            'type'      => 'textarea',
            'group'     => 'general',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[500]',
            'help'      => 'Deskripsi umum yang dipakai untuk footer dan fallback metadata.',
        ],
        'contact_email' => [
            'label'     => 'Public Email',
            'input'     => 'email',
            'type'      => 'email',
            'group'     => 'contact',
            'is_public' => 1,
            'rules'     => 'permit_empty|valid_email|max_length[120]',
            'help'      => 'Email publik pada section contact. Tidak digunakan sebagai kredensial SMTP.',
        ],
        'contact_phone' => [
            'label'     => 'Public Phone',
            'input'     => 'text',
            'type'      => 'text',
            'group'     => 'contact',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[50]',
            'help'      => 'Opsional. Tampilkan hanya jika memang ingin dipublikasikan.',
        ],
        'contact_location' => [
            'label'     => 'Public Location',
            'input'     => 'text',
            'type'      => 'text',
            'group'     => 'contact',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[180]',
            'help'      => 'Lokasi publik seperti kota/negara, bukan alamat pribadi detail.',
        ],
        'seo_title' => [
            'label'     => 'SEO Title',
            'input'     => 'text',
            'type'      => 'text',
            'group'     => 'seo',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[180]',
            'help'      => 'Dipakai sebagai title homepage jika diisi.',
        ],
        'seo_description' => [
            'label'     => 'SEO Description',
            'input'     => 'textarea',
            'type'      => 'textarea',
            'group'     => 'seo',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[320]',
            'help'      => 'Meta description singkat untuk homepage.',
        ],
        'seo_keywords' => [
            'label'     => 'SEO Keywords',
            'input'     => 'text',
            'type'      => 'text',
            'group'     => 'seo',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[255]',
            'help'      => 'Opsional. Pisahkan dengan koma.',
        ],
        'canonical_url' => [
            'label'     => 'Canonical URL',
            'input'     => 'url',
            'type'      => 'url',
            'group'     => 'seo',
            'is_public' => 1,
            'rules'     => 'permit_empty|valid_url|max_length[255]',
            'help'      => 'URL utama website, contoh: https://domain.com/.',
        ],
        'favicon_url' => [
            'label'     => 'Favicon',
            'input'     => 'text',
            'type'      => 'url',
            'group'     => 'brand',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[255]',
            'help'      => 'URL penuh atau path lokal seperti /favicon.ico atau uploads/settings/favicon.png.',
        ],
        'logo_url' => [
            'label'     => 'Logo',
            'input'     => 'text',
            'type'      => 'url',
            'group'     => 'brand',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[255]',
            'help'      => 'URL penuh atau path lokal logo. Kosongkan untuk memakai teks site title.',
        ],
        'default_og_image_url' => [
            'label'     => 'Default Open Graph Image',
            'input'     => 'text',
            'type'      => 'url',
            'group'     => 'brand',
            'is_public' => 1,
            'rules'     => 'permit_empty|max_length[255]',
            'help'      => 'Fallback image saat halaman tidak punya gambar khusus.',
        ],
    ];

    public function index()
    {
        return view('admin/settings/index', [
            'title'              => 'Site Settings',
            'breadcrumb'         => ['Settings'],
            'user'               => $this->currentUser(),
            'settingDefinitions' => self::SITE_SETTINGS,
            'siteSettings'       => $this->siteSettings(),
        ]);
    }

    public function updateAccount()
    {
        $user = $this->currentUser();

        if (! $user) {
            return redirect()->to(site_url('admin/login'))->with('error', 'Session admin tidak valid.');
        }

        $newPassword = (string) $this->request->getPost('new_password');
        $rules = [
            'username'         => 'required|max_length[50]',
            'email'            => 'required|valid_email|max_length[120]',
            'current_password' => 'permit_empty',
            'new_password'     => 'permit_empty|min_length[8]',
            'password_confirm' => 'matches[new_password]',
        ];

        if ($newPassword !== '') {
            $rules['current_password'] = 'required';
            $rules['password_confirm'] = 'required|matches[new_password]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $email = trim((string) $this->request->getPost('email'));
        $userModel = new UserModel();

        if ($userModel->where('username', $username)->where('id !=', $user['id'])->first()) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan.');
        }

        if ($userModel->where('email', $email)->where('id !=', $user['id'])->first()) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan.');
        }

        $data = [
            'username' => $username,
            'email'    => $email,
        ];

        if ($newPassword !== '') {
            $currentPassword = (string) $this->request->getPost('current_password');

            if (! password_verify($currentPassword, $user['password_hash'])) {
                return redirect()->back()->withInput()->with('error', 'Password saat ini tidak sesuai.');
            }

            $data['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userModel->skipValidation(true)->update($user['id'], $data);
        session()->set([
            'name'  => $username,
            'email' => $email,
        ]);

        return redirect()->to(site_url('admin/settings'))->with('success', 'User admin berhasil diperbarui.');
    }

    public function updateSite()
    {
        $rules = [];

        foreach (self::SITE_SETTINGS as $key => $definition) {
            $rules[$key] = $definition['rules'];
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        foreach (['favicon_url', 'logo_url', 'default_og_image_url'] as $key) {
            $value = trim((string) $this->request->getPost($key));

            if (! $this->isSafePublicAssetReference($value)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Field ' . self::SITE_SETTINGS[$key]['label'] . ' harus berupa URL http/https atau path public yang aman.');
            }
        }

        $model = new SiteSettingModel();

        foreach (self::SITE_SETTINGS as $key => $definition) {
            $value = trim((string) $this->request->getPost($key));
            $existing = $model->where('setting_key', $key)->first();
            $data = [
                'setting_key'   => $key,
                'setting_value' => $value,
                'type'          => $definition['type'],
                'setting_group' => $definition['group'],
                'is_public'     => $definition['is_public'],
            ];

            if ($existing) {
                $model->skipValidation(true)->update($existing['id'], $data);
                continue;
            }

            $model->skipValidation(true)->insert($data);
        }

        cache()->clean();

        return redirect()->to(site_url('admin/settings'))->with('success', 'Site settings berhasil diperbarui.');
    }

    private function isSafePublicAssetReference(string $value): bool
    {
        if ($value === '') {
            return true;
        }

        if (preg_match('/[\x00-\x1F\x7F]/', $value) || str_contains($value, '..')) {
            return false;
        }

        if (preg_match('/^https?:\/\//i', $value)) {
            return filter_var($value, FILTER_VALIDATE_URL) !== false;
        }

        if (preg_match('/^[a-z][a-z0-9+.-]*:/i', $value)) {
            return false;
        }

        return preg_match('#^/?[A-Za-z0-9._/-]+$#', $value) === 1;
    }

    private function currentUser(): ?array
    {
        $userId = (int) session('user_id');

        if ($userId < 1) {
            return null;
        }

        return (new UserModel())->find($userId);
    }

    private function siteSettings(): array
    {
        $settings = [];
        $rows = (new SiteSettingModel())->findAll();

        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return $settings;
    }
}
