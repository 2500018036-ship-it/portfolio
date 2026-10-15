<?php

namespace App\Controllers\Admin;

use App\Models\EducationModel;

class EducationController extends AdminController
{
    public function index()
    {
        return view('admin/education/index', [
            'title'      => 'Education',
            'breadcrumb' => ['Education'],
            'items'      => (new EducationModel())->orderBy('sort_order', 'ASC')->orderBy('start_date', 'DESC')->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $item = (new EducationModel())->find($id);

        if (! $item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Education tidak ditemukan.');
        }

        return view('admin/education/show', [
            'title'      => 'Education Detail',
            'breadcrumb' => ['Education', 'Detail'],
            'item'       => $item,
        ]);
    }

    public function new()
    {
        return view('admin/education/form', [
            'title'      => 'Create Education',
            'breadcrumb' => ['Education', 'Create'],
            'item'       => null,
            'action'     => site_url('admin/education'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new EducationModel())->skipValidation(true)->insert($this->payload());

        return redirect()->to(site_url('admin/education'))->with('success', 'Education berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $item = (new EducationModel())->find($id);

        if (! $item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Education tidak ditemukan.');
        }

        return view('admin/education/form', [
            'title'      => 'Edit Education',
            'breadcrumb' => ['Education', 'Edit'],
            'item'       => $item,
            'action'     => site_url('admin/education/' . $id),
        ]);
    }

    public function update(int $id)
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new EducationModel())->skipValidation(true)->update($id, $this->payload());

        return redirect()->to(site_url('admin/education'))->with('success', 'Education berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        (new EducationModel())->delete($id);

        return redirect()->to(site_url('admin/education'))->with('success', 'Education berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'institution' => 'required|max_length[180]',
            'program'     => 'permit_empty|max_length[180]',
            'start_date'  => 'permit_empty|valid_date[Y-m-d]',
            'end_date'    => 'permit_empty|valid_date[Y-m-d]',
            'sort_order'  => 'permit_empty|integer',
            'status'      => 'required|in_list[draft,published,archived]',
        ];
    }

    private function payload(): array
    {
        return [
            'institution' => trim((string) $this->request->getPost('institution')),
            'program'     => trim((string) $this->request->getPost('program')),
            'degree'      => trim((string) $this->request->getPost('program')),
            'major'       => null,
            'start_date'  => $this->request->getPost('start_date') ?: null,
            'end_date'    => $this->request->getPost('is_current') ? null : ($this->request->getPost('end_date') ?: null),
            'is_current'  => $this->request->getPost('is_current') ? 1 : 0,
            'description' => trim((string) $this->request->getPost('description')),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'      => (string) $this->request->getPost('status'),
        ];
    }
}
