<?php

namespace App\Controllers\Admin;

use App\Models\AchievementModel;
use RuntimeException;

class AchievementController extends AdminController
{
    public function index()
    {
        return view('admin/achievements/index', [
            'title'        => 'Achievements',
            'breadcrumb'   => ['Achievements'],
            'achievements' => (new AchievementModel())->orderBy('sort_order', 'ASC')->orderBy('date', 'DESC')->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $achievement = (new AchievementModel())->find($id);

        if (! $achievement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Achievement tidak ditemukan.');
        }

        return view('admin/achievements/show', [
            'title'       => 'Achievement Detail',
            'breadcrumb'  => ['Achievements', 'Detail'],
            'achievement' => $achievement,
        ]);
    }

    public function new()
    {
        return view('admin/achievements/form', [
            'title'       => 'Create Achievement',
            'breadcrumb'  => ['Achievements', 'Create'],
            'achievement' => null,
            'action'      => site_url('admin/achievements'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->payload();

        try {
            $image = $this->storeUploadedFile('image', 'achievements', ['jpg', 'jpeg', 'png', 'webp'], 3072);
            $document = $this->storeUploadedFile('document', 'achievements', ['pdf', 'doc', 'docx'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($image) {
            $data['image'] = $image;
        }

        if ($document) {
            $data['document'] = $document;
        }

        (new AchievementModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/achievements'))->with('success', 'Achievement berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $achievement = (new AchievementModel())->find($id);

        if (! $achievement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Achievement tidak ditemukan.');
        }

        return view('admin/achievements/form', [
            'title'       => 'Edit Achievement',
            'breadcrumb'  => ['Achievements', 'Edit'],
            'achievement' => $achievement,
            'action'      => site_url('admin/achievements/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new AchievementModel();
        $achievement = $model->find($id);

        if (! $achievement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Achievement tidak ditemukan.');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->payload();

        try {
            $image = $this->storeUploadedFile('image', 'achievements', ['jpg', 'jpeg', 'png', 'webp'], 3072);
            $document = $this->storeUploadedFile('document', 'achievements', ['pdf', 'doc', 'docx'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($image) {
            $this->removePublicUpload($achievement['image'] ?? null);
            $data['image'] = $image;
        }

        if ($document) {
            $this->removePublicUpload($achievement['document'] ?? null);
            $data['document'] = $document;
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/achievements'))->with('success', 'Achievement berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $achievement = (new AchievementModel())->find($id);

        if ($achievement) {
            $this->removePublicUpload($achievement['image'] ?? null);
            $this->removePublicUpload($achievement['document'] ?? null);
            (new AchievementModel())->delete($id);
        }

        return redirect()->to(site_url('admin/achievements'))->with('success', 'Achievement berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'title'      => 'required|max_length[180]',
            'date'       => 'permit_empty|valid_date[Y-m-d]',
            'sort_order' => 'permit_empty|integer',
            'status'     => 'required|in_list[draft,published,archived]',
        ];
    }

    private function payload(): array
    {
        return [
            'title'        => trim((string) $this->request->getPost('title')),
            'description'  => trim((string) $this->request->getPost('description')),
            'organization' => trim((string) $this->request->getPost('organization')),
            'date'         => $this->request->getPost('date') ?: null,
            'sort_order'   => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'       => (string) $this->request->getPost('status'),
        ];
    }
}
