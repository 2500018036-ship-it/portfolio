<?php

namespace App\Controllers\Admin;

use App\Models\ExperienceModel;

class ExperienceController extends AdminController
{
    public function index()
    {
        return view('admin/experience/index', [
            'title'      => 'Experience',
            'breadcrumb' => ['Experience'],
            'items'      => (new ExperienceModel())->orderBy('sort_order', 'ASC')->orderBy('start_date', 'DESC')->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $item = (new ExperienceModel())->find($id);

        if (! $item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Experience tidak ditemukan.');
        }

        return view('admin/experience/show', [
            'title'      => 'Experience Detail',
            'breadcrumb' => ['Experience', 'Detail'],
            'item'       => $item,
        ]);
    }

    public function new()
    {
        return view('admin/experience/form', [
            'title'      => 'Create Experience',
            'breadcrumb' => ['Experience', 'Create'],
            'item'       => null,
            'action'     => site_url('admin/experience'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new ExperienceModel())->skipValidation(true)->insert($this->payload());

        return redirect()->to(site_url('admin/experience'))->with('success', 'Experience berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $item = (new ExperienceModel())->find($id);

        if (! $item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Experience tidak ditemukan.');
        }

        return view('admin/experience/form', [
            'title'      => 'Edit Experience',
            'breadcrumb' => ['Experience', 'Edit'],
            'item'       => $item,
            'action'     => site_url('admin/experience/' . $id),
        ]);
    }

    public function update(int $id)
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new ExperienceModel())->skipValidation(true)->update($id, $this->payload());

        return redirect()->to(site_url('admin/experience'))->with('success', 'Experience berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        (new ExperienceModel())->delete($id);

        return redirect()->to(site_url('admin/experience'))->with('success', 'Experience berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'company'    => 'required|max_length[160]',
            'position'   => 'required|max_length[160]',
            'location'   => 'permit_empty|max_length[160]',
            'start_date' => 'permit_empty|valid_date[Y-m-d]',
            'end_date'   => 'permit_empty|valid_date[Y-m-d]',
            'sort_order' => 'permit_empty|integer',
            'status'     => 'required|in_list[draft,published,archived]',
        ];
    }

    private function payload(): array
    {
        return [
            'company'         => trim((string) $this->request->getPost('company')),
            'position'        => trim((string) $this->request->getPost('position')),
            'location'        => trim((string) $this->request->getPost('location')),
            'employment_type' => trim((string) $this->request->getPost('employment_type')),
            'start_date'      => $this->request->getPost('start_date') ?: null,
            'end_date'        => $this->request->getPost('is_current') ? null : ($this->request->getPost('end_date') ?: null),
            'is_current'      => $this->request->getPost('is_current') ? 1 : 0,
            'description'     => trim((string) $this->request->getPost('description')),
            'technologies'    => trim((string) $this->request->getPost('technologies')),
            'sort_order'      => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'          => (string) $this->request->getPost('status'),
        ];
    }
}
