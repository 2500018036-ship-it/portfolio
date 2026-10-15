<?php

namespace App\Controllers\Admin;

use App\Models\SocialLinkModel;

class SocialLinkController extends AdminController
{
    public function index()
    {
        return view('admin/social_links/index', [
            'title'      => 'Social Links',
            'breadcrumb' => ['Social Links'],
            'links'      => (new SocialLinkModel())->orderBy('sort_order', 'ASC')->orderBy('platform', 'ASC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('admin/social_links/form', [
            'title'      => 'Create Social Link',
            'breadcrumb' => ['Social Links', 'Create'],
            'link'       => null,
            'action'     => site_url('admin/social-links'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new SocialLinkModel())->skipValidation(true)->insert($this->payload());

        return redirect()->to(site_url('admin/social-links'))->with('success', 'Social link berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $link = (new SocialLinkModel())->find($id);

        if (! $link) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Social link tidak ditemukan.');
        }

        return view('admin/social_links/form', [
            'title'      => 'Edit Social Link',
            'breadcrumb' => ['Social Links', 'Edit'],
            'link'       => $link,
            'action'     => site_url('admin/social-links/' . $id),
        ]);
    }

    public function update(int $id)
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new SocialLinkModel())->skipValidation(true)->update($id, $this->payload());

        return redirect()->to(site_url('admin/social-links'))->with('success', 'Social link berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        (new SocialLinkModel())->delete($id);

        return redirect()->to(site_url('admin/social-links'))->with('success', 'Social link berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'platform'   => 'required|max_length[60]',
            'label'      => 'permit_empty|max_length[100]',
            'url'        => 'required|valid_url|max_length[255]',
            'icon'       => 'permit_empty|max_length[100]',
            'sort_order' => 'permit_empty|integer',
            'status'     => 'required|in_list[active,inactive]',
        ];
    }

    private function payload(): array
    {
        return [
            'platform'   => trim((string) $this->request->getPost('platform')),
            'label'      => trim((string) $this->request->getPost('label')),
            'url'        => trim((string) $this->request->getPost('url')),
            'icon'       => trim((string) $this->request->getPost('icon')),
            'sort_order' => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'     => (string) $this->request->getPost('status'),
        ];
    }
}
