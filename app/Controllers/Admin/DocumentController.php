<?php

namespace App\Controllers\Admin;

use App\Models\DocumentModel;
use RuntimeException;

class DocumentController extends AdminController
{
    private const CATEGORIES = [
        'CV',
        'Resume',
        'Portfolio PDF',
        'Technical Document',
        'Other Professional Document',
    ];

    public function index()
    {
        return view('admin/documents/index', [
            'title'      => 'Documents',
            'breadcrumb' => ['Documents'],
            'documents'  => (new DocumentModel())
                ->orderBy('sort_order', 'ASC')
                ->orderBy('uploaded_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->findAll(),
            'categories' => self::CATEGORIES,
        ]);
    }

    public function new()
    {
        return view('admin/documents/form', [
            'title'      => 'Create Document',
            'breadcrumb' => ['Documents', 'Create'],
            'document'   => null,
            'categories' => self::CATEGORIES,
            'action'     => site_url('admin/documents'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->documentRules(true))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->documentPayload();

        try {
            $file = $this->storeProtectedFile('file', 'documents', ['pdf', 'doc', 'docx'], 10240);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if (! $file) {
            return redirect()->back()->withInput()->with('error', 'File dokumen wajib diupload.');
        }

        $data['file'] = $file;
        $data['uploaded_at'] = date('Y-m-d H:i:s');

        (new DocumentModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/documents'))->with('success', 'Dokumen berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $document = (new DocumentModel())->find($id);

        if (! $document) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        return view('admin/documents/form', [
            'title'      => 'Edit Document',
            'breadcrumb' => ['Documents', 'Edit'],
            'document'   => $document,
            'categories' => self::CATEGORIES,
            'action'     => site_url('admin/documents/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new DocumentModel();
        $document = $model->find($id);

        if (! $document) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        if (! $this->validate($this->documentRules(false))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->documentPayload();

        try {
            $file = $this->storeProtectedFile('file', 'documents', ['pdf', 'doc', 'docx'], 10240);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($file) {
            $this->removeProtectedUpload($document['file'] ?? null);
            $data['file'] = $file;
            $data['uploaded_at'] = date('Y-m-d H:i:s');
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/documents'))->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $document = (new DocumentModel())->find($id);

        if ($document) {
            $this->removeProtectedUpload($document['file'] ?? null);
            (new DocumentModel())->delete($id);
        }

        return redirect()->to(site_url('admin/documents'))->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(int $id)
    {
        $document = (new DocumentModel())->find($id);

        if (! $document) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        return $this->protectedDownload($document['file'] ?? null, url_title($document['title'], '-', true));
    }

    private function documentRules(bool $isCreate): array
    {
        return [
            'title'      => 'required|max_length[180]',
            'category'   => 'required|in_list[' . implode(',', self::CATEGORIES) . ']',
            'version'    => 'permit_empty|max_length[50]',
            'visibility' => 'required|in_list[public,private]',
            'status'     => 'required|in_list[draft,published,archived]',
            'sort_order' => 'permit_empty|integer',
            'file'       => $isCreate ? 'uploaded[file]' : 'permit_empty',
        ];
    }

    private function documentPayload(): array
    {
        return [
            'title'       => trim((string) $this->request->getPost('title')),
            'category'    => (string) $this->request->getPost('category'),
            'description' => trim((string) $this->request->getPost('description')),
            'version'     => trim((string) $this->request->getPost('version')),
            'visibility'  => (string) $this->request->getPost('visibility'),
            'status'      => (string) $this->request->getPost('status'),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?: 0),
        ];
    }

    private function protectedDownload(?string $relativePath, string $fileName)
    {
        $path = portfolio_upload_path($relativePath, 'File dokumen tidak ditemukan.');

        return $this->response->download($path, null)->setFileName($fileName . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }
}
