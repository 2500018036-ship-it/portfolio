<?php

namespace App\Controllers\Admin;

use App\Models\DocumentationModel;
use RuntimeException;

class DocumentationController extends AdminController
{
    public function index()
    {
        return view('admin/documentation/index', [
            'title'         => 'Documentation',
            'breadcrumb'    => ['Documentation'],
            'documentation' => (new DocumentationModel())
                ->orderBy('sort_order', 'ASC')
                ->orderBy('published_at', 'DESC')
                ->orderBy('updated_at', 'DESC')
                ->findAll(),
        ]);
    }

    public function new()
    {
        return view('admin/documentation/form', [
            'title'    => 'Create Documentation',
            'breadcrumb' => ['Documentation', 'Create'],
            'document' => null,
            'action'   => site_url('admin/documentation'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = $this->payload();
            $image = $this->storeUploadedFile('featured_image', 'documentation', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($this->slugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug documentation sudah digunakan.');
        }

        if ($image) {
            $data['featured_image'] = $image;
            $data['thumbnail'] = $image;
        }

        (new DocumentationModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/documentation'))->with('success', 'Documentation berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $document = (new DocumentationModel())->find($id);

        if (! $document) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Documentation tidak ditemukan.');
        }

        return view('admin/documentation/form', [
            'title'      => 'Edit Documentation',
            'breadcrumb' => ['Documentation', 'Edit'],
            'document'   => $document,
            'action'     => site_url('admin/documentation/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new DocumentationModel();
        $document = $model->find($id);

        if (! $document) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Documentation tidak ditemukan.');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = $this->payload();
            $image = $this->storeUploadedFile('featured_image', 'documentation', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($this->slugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug documentation sudah digunakan.');
        }

        if ($image) {
            $this->removePublicUpload($document['featured_image'] ?: ($document['thumbnail'] ?? null));
            $data['featured_image'] = $image;
            $data['thumbnail'] = $image;
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/documentation'))->with('success', 'Documentation berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $document = (new DocumentationModel())->find($id);

        if ($document) {
            $this->removePublicUpload($document['featured_image'] ?: ($document['thumbnail'] ?? null));
            (new DocumentationModel())->delete($id);
        }

        return redirect()->to(site_url('admin/documentation'))->with('success', 'Documentation berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'title'        => 'required|max_length[180]',
            'slug'         => 'permit_empty|max_length[200]',
            'category'     => 'permit_empty|max_length[100]',
            'summary'      => 'permit_empty|max_length[500]',
            'tags'         => 'permit_empty|max_length[255]',
            'author'       => 'permit_empty|max_length[120]',
            'status'       => 'required|in_list[draft,published,archived]',
            'published_at' => 'permit_empty|max_length[25]',
            'sort_order'   => 'permit_empty|integer',
        ];
    }

    private function payload(): array
    {
        $title = trim((string) $this->request->getPost('title'));
        $summary = trim((string) $this->request->getPost('summary'));
        $status = (string) $this->request->getPost('status');

        return [
            'title'        => $title,
            'slug'         => $this->slugFromInput($title, $this->request->getPost('slug')),
            'category'     => trim((string) $this->request->getPost('category')),
            'summary'      => $summary,
            'excerpt'      => $summary,
            'content'      => $this->sanitizeContentHtml($this->request->getPost('content')),
            'tags'         => trim((string) $this->request->getPost('tags')),
            'author'       => trim((string) $this->request->getPost('author')),
            'status'       => $status,
            'published_at' => $this->publishedAt($this->request->getPost('published_at'), $status),
            'sort_order'   => (int) ($this->request->getPost('sort_order') ?: 0),
        ];
    }

    private function publishedAt(?string $value, string $status): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return $status === 'published' ? date('Y-m-d H:i:s') : null;
        }

        $timestamp = strtotime($value);

        if ($timestamp === false) {
            throw new RuntimeException('Published date tidak valid.');
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new DocumentationModel())->withDeleted()->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }
}
