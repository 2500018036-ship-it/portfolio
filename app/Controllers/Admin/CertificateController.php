<?php

namespace App\Controllers\Admin;

use App\Models\CertificateCategoryModel;
use App\Models\CertificateModel;
use RuntimeException;

class CertificateController extends AdminController
{
    public function index()
    {
        return view('admin/certificates/index', [
            'title'        => 'Certificates',
            'breadcrumb'   => ['Certificates'],
            'certificates' => (new CertificateModel())
                ->select('certificates.*, certificate_categories.name AS category_name')
                ->join('certificate_categories', 'certificate_categories.id = certificates.category_id', 'left')
                ->orderBy('certificates.sort_order', 'ASC')
                ->orderBy('certificates.issue_date', 'DESC')
                ->findAll(),
            'categories'   => (new CertificateCategoryModel())->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $certificate = (new CertificateModel())
            ->select('certificates.*, certificate_categories.name AS category_name')
            ->join('certificate_categories', 'certificate_categories.id = certificates.category_id', 'left')
            ->find($id);

        if (! $certificate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Certificate tidak ditemukan.');
        }

        return view('admin/certificates/show', [
            'title'       => 'Certificate Detail',
            'breadcrumb'  => ['Certificates', 'Detail'],
            'certificate' => $certificate,
        ]);
    }

    public function new()
    {
        return view('admin/certificates/form', [
            'title'       => 'Create Certificate',
            'breadcrumb'  => ['Certificates', 'Create'],
            'certificate' => null,
            'categories'  => (new CertificateCategoryModel())->orderBy('name', 'ASC')->findAll(),
            'action'      => site_url('admin/certificates'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->certificateRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->certificatePayload();

        if ($this->certificateSlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug certificate sudah digunakan.');
        }

        try {
            $image = $this->storeProtectedFile('certificate_image', 'certificates/images', ['jpg', 'jpeg', 'png', 'webp'], 4096);
            $pdf = $this->storeProtectedFile('certificate_file', 'certificates/files', ['pdf'], 8192);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($image) {
            $data['certificate_image'] = $image;
        }

        if ($pdf) {
            $data['certificate_file'] = $pdf;
        }

        (new CertificateModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/certificates'))->with('success', 'Certificate berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $certificate = (new CertificateModel())->find($id);

        if (! $certificate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Certificate tidak ditemukan.');
        }

        return view('admin/certificates/form', [
            'title'       => 'Edit Certificate',
            'breadcrumb'  => ['Certificates', 'Edit'],
            'certificate' => $certificate,
            'categories'  => (new CertificateCategoryModel())->orderBy('name', 'ASC')->findAll(),
            'action'      => site_url('admin/certificates/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new CertificateModel();
        $certificate = $model->find($id);

        if (! $certificate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Certificate tidak ditemukan.');
        }

        if (! $this->validate($this->certificateRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->certificatePayload();

        if ($this->certificateSlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug certificate sudah digunakan.');
        }

        try {
            $image = $this->storeProtectedFile('certificate_image', 'certificates/images', ['jpg', 'jpeg', 'png', 'webp'], 4096);
            $pdf = $this->storeProtectedFile('certificate_file', 'certificates/files', ['pdf'], 8192);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($image) {
            $this->removeProtectedUpload($certificate['certificate_image'] ?? null);
            $data['certificate_image'] = $image;
        }

        if ($pdf) {
            $this->removeProtectedUpload($certificate['certificate_file'] ?? null);
            $data['certificate_file'] = $pdf;
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/certificates'))->with('success', 'Certificate berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $certificate = (new CertificateModel())->find($id);

        if ($certificate) {
            $this->removeProtectedUpload($certificate['certificate_image'] ?? null);
            $this->removeProtectedUpload($certificate['certificate_file'] ?? null);
            (new CertificateModel())->delete($id);
        }

        return redirect()->to(site_url('admin/certificates'))->with('success', 'Certificate berhasil dihapus.');
    }

    public function download(int $id, string $type)
    {
        $certificate = (new CertificateModel())->find($id);

        if (! $certificate || ! in_array($type, ['image', 'file'], true)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File certificate tidak ditemukan.');
        }

        $path = $type === 'image' ? ($certificate['certificate_image'] ?? null) : ($certificate['certificate_file'] ?? null);

        return $this->protectedDownload($path, $certificate['slug'] ?: url_title($certificate['title'], '-', true));
    }

    public function newCategory()
    {
        return view('admin/certificates/category_form', [
            'title'      => 'Create Certificate Category',
            'breadcrumb' => ['Certificates', 'Create Category'],
            'category'   => null,
            'action'     => site_url('admin/certificates/categories'),
        ]);
    }

    public function createCategory()
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori certificate sudah digunakan.');
        }

        (new CertificateCategoryModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/certificates'))->with('success', 'Kategori certificate berhasil dibuat.');
    }

    public function editCategory(int $id)
    {
        $category = (new CertificateCategoryModel())->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori certificate tidak ditemukan.');
        }

        return view('admin/certificates/category_form', [
            'title'      => 'Edit Certificate Category',
            'breadcrumb' => ['Certificates', 'Edit Category'],
            'category'   => $category,
            'action'     => site_url('admin/certificates/categories/' . $id),
        ]);
    }

    public function updateCategory(int $id)
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori certificate sudah digunakan.');
        }

        (new CertificateCategoryModel())->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/certificates'))->with('success', 'Kategori certificate berhasil diperbarui.');
    }

    public function deleteCategory(int $id)
    {
        $count = (new CertificateModel())->where('category_id', $id)->countAllResults();

        if ($count > 0) {
            return redirect()->to(site_url('admin/certificates'))->with('error', 'Kategori masih digunakan oleh certificate.');
        }

        (new CertificateCategoryModel())->delete($id);

        return redirect()->to(site_url('admin/certificates'))->with('success', 'Kategori certificate berhasil dihapus.');
    }

    private function certificateRules(): array
    {
        return [
            'title'            => 'required|max_length[180]',
            'slug'             => 'permit_empty|max_length[200]',
            'category_id'      => 'permit_empty|is_natural_no_zero',
            'issuer'           => 'permit_empty|max_length[160]',
            'credential_id'    => 'permit_empty|max_length[160]',
            'issue_date'       => 'permit_empty|valid_date[Y-m-d]',
            'expiration_date'  => 'permit_empty|valid_date[Y-m-d]',
            'verification_url' => 'permit_empty|valid_url|max_length[255]',
            'sort_order'       => 'permit_empty|integer',
            'status'           => 'required|in_list[draft,published,archived]',
        ];
    }

    private function certificatePayload(): array
    {
        $title = trim((string) $this->request->getPost('title'));

        return [
            'category_id'      => $this->request->getPost('category_id') ? (int) $this->request->getPost('category_id') : null,
            'title'            => $title,
            'slug'             => $this->slugFromInput($title, $this->request->getPost('slug')),
            'issuer'           => trim((string) $this->request->getPost('issuer')),
            'credential_id'    => trim((string) $this->request->getPost('credential_id')),
            'description'      => trim((string) $this->request->getPost('description')),
            'skills_related'   => trim((string) $this->request->getPost('skills_related')),
            'issue_date'       => $this->request->getPost('issue_date') ?: null,
            'expiration_date'  => $this->request->getPost('expiration_date') ?: null,
            'verification_url' => trim((string) $this->request->getPost('verification_url')),
            'sort_order'       => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'           => (string) $this->request->getPost('status'),
        ];
    }

    private function categoryRules(): array
    {
        return [
            'name'       => 'required|max_length[100]',
            'slug'       => 'permit_empty|max_length[120]',
            'sort_order' => 'permit_empty|integer',
            'status'     => 'required|in_list[active,inactive]',
        ];
    }

    private function categoryPayload(): array
    {
        $name = trim((string) $this->request->getPost('name'));

        return [
            'name'        => $name,
            'slug'        => $this->slugFromInput($name, $this->request->getPost('slug')),
            'description' => trim((string) $this->request->getPost('description')),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'      => (string) $this->request->getPost('status'),
        ];
    }

    private function certificateSlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new CertificateModel())->withDeleted()->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }

    private function categorySlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new CertificateCategoryModel())->withDeleted()->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }

    private function protectedDownload(?string $relativePath, string $fileName)
    {
        $path = portfolio_upload_path($relativePath, 'File certificate tidak ditemukan.');

        return $this->response->download($path, null)->setFileName($fileName . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }
}
