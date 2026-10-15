<?php

namespace App\Controllers\Admin;

use App\Models\ProfileModel;
use RuntimeException;

class ProfileController extends AdminController
{
    public function index()
    {
        $profile = (new ProfileModel())->orderBy('id', 'ASC')->first();

        return view('admin/profile/index', [
            'title'      => 'Profile',
            'breadcrumb' => ['Profile'],
            'profile'    => $profile,
        ]);
    }

    public function new()
    {
        return view('admin/profile/form', [
            'title'      => 'Create Profile',
            'breadcrumb' => ['Profile', 'Create'],
            'profile'    => null,
            'action'     => site_url('admin/profile'),
        ]);
    }

    public function create()
    {
        $model = new ProfileModel();

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->payload();

        try {
            $image = $this->storeUploadedFile('profile_image', 'profile', ['jpg', 'jpeg', 'png', 'webp'], 2048);
            $resume = $this->storeUploadedFile('resume_file', 'profile', ['pdf', 'doc', 'docx'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($image) {
            $data['profile_image'] = $image;
        }

        if ($resume) {
            $data['resume_file'] = $resume;
        }

        $model->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/profile'))->with('success', 'Profile berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $profile = (new ProfileModel())->find($id);

        if (! $profile) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Profile tidak ditemukan.');
        }

        return view('admin/profile/form', [
            'title'      => 'Edit Profile',
            'breadcrumb' => ['Profile', 'Edit'],
            'profile'    => $profile,
            'action'     => site_url('admin/profile/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new ProfileModel();
        $profile = $model->find($id);

        if (! $profile) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Profile tidak ditemukan.');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->payload();

        try {
            $image = $this->storeUploadedFile('profile_image', 'profile', ['jpg', 'jpeg', 'png', 'webp'], 2048);
            $resume = $this->storeUploadedFile('resume_file', 'profile', ['pdf', 'doc', 'docx'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($image) {
            $this->removePublicUpload($profile['profile_image'] ?? null);
            $data['profile_image'] = $image;
        }

        if ($resume) {
            $this->removePublicUpload($profile['resume_file'] ?? null);
            $data['resume_file'] = $resume;
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/profile'))->with('success', 'Profile berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $profile = (new ProfileModel())->find($id);

        if ($profile) {
            $this->removePublicUpload($profile['profile_image'] ?? null);
            $this->removePublicUpload($profile['resume_file'] ?? null);
            (new ProfileModel())->delete($id);
        }

        return redirect()->to(site_url('admin/profile'))->with('success', 'Profile berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'name'         => 'required|max_length[120]',
            'email'        => 'permit_empty|valid_email|max_length[120]',
            'github_url'   => 'permit_empty|valid_url|max_length[255]',
            'linkedin_url' => 'permit_empty|valid_url|max_length[255]',
            'website_url'  => 'permit_empty|valid_url|max_length[255]',
            'status'       => 'required|in_list[active,inactive]',
        ];
    }

    private function payload(): array
    {
        return [
            'name'               => trim((string) $this->request->getPost('name')),
            'professional_title' => trim((string) $this->request->getPost('professional_title')),
            'headline'           => trim((string) $this->request->getPost('headline')),
            'short_bio'          => trim((string) $this->request->getPost('short_bio')),
            'full_bio'           => trim((string) $this->request->getPost('full_bio')),
            'location'           => trim((string) $this->request->getPost('location')),
            'email'              => trim((string) $this->request->getPost('email')),
            'phone'              => trim((string) $this->request->getPost('phone')),
            'career_focus'       => trim((string) $this->request->getPost('career_focus')),
            'availability'       => trim((string) $this->request->getPost('availability')),
            'github_url'         => trim((string) $this->request->getPost('github_url')),
            'linkedin_url'       => trim((string) $this->request->getPost('linkedin_url')),
            'website_url'        => trim((string) $this->request->getPost('website_url')),
            'status'             => (string) $this->request->getPost('status'),
        ];
    }
}
