<?php

namespace App\Controllers\Admin;

use App\Models\GalleryCategoryModel;
use App\Models\GalleryModel;
use RuntimeException;

class GalleryController extends AdminController
{
    public function index()
    {
        return view('admin/gallery/index', [
            'title'      => 'Gallery',
            'breadcrumb' => ['Gallery'],
            'items'      => (new GalleryModel())
                ->select('gallery.*, gallery_categories.name AS category_name')
                ->join('gallery_categories', 'gallery_categories.id = gallery.category_id', 'left')
                ->orderBy('gallery.sort_order', 'ASC')
                ->orderBy('gallery.date', 'DESC')
                ->orderBy('gallery.created_at', 'DESC')
                ->findAll(),
            'categories' => (new GalleryCategoryModel())->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('admin/gallery/form', [
            'title'      => 'Create Gallery Item',
            'breadcrumb' => ['Gallery', 'Create'],
            'item'       => null,
            'categories' => (new GalleryCategoryModel())->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'action'     => site_url('admin/gallery'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->rules(true))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $image = $this->storeUploadedFile('image', 'gallery', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if (! $image) {
            return redirect()->back()->withInput()->with('error', 'Image wajib diupload.');
        }

        $data = $this->payload();
        $data['image'] = $image;

        (new GalleryModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/gallery'))->with('success', 'Gallery item berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $item = (new GalleryModel())->find($id);

        if (! $item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Gallery item tidak ditemukan.');
        }

        return view('admin/gallery/form', [
            'title'      => 'Edit Gallery Item',
            'breadcrumb' => ['Gallery', 'Edit'],
            'item'       => $item,
            'categories' => (new GalleryCategoryModel())->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'action'     => site_url('admin/gallery/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new GalleryModel();
        $item = $model->find($id);

        if (! $item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Gallery item tidak ditemukan.');
        }

        if (! $this->validate($this->rules(false))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $image = $this->storeUploadedFile('image', 'gallery', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $data = $this->payload();

        if ($image) {
            $this->removePublicUpload($item['image'] ?? null);
            $data['image'] = $image;
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/gallery'))->with('success', 'Gallery item berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $item = (new GalleryModel())->find($id);

        if ($item) {
            $this->removePublicUpload($item['image'] ?? null);
            (new GalleryModel())->delete($id);
        }

        return redirect()->to(site_url('admin/gallery'))->with('success', 'Gallery item berhasil dihapus.');
    }

    public function newCategory()
    {
        return view('admin/gallery/category_form', [
            'title'      => 'Create Gallery Category',
            'breadcrumb' => ['Gallery', 'Create Category'],
            'category'   => null,
            'action'     => site_url('admin/gallery/categories'),
        ]);
    }

    public function createCategory()
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori gallery sudah digunakan.');
        }

        (new GalleryCategoryModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/gallery'))->with('success', 'Kategori gallery berhasil dibuat.');
    }

    public function editCategory(int $id)
    {
        $category = (new GalleryCategoryModel())->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori gallery tidak ditemukan.');
        }

        return view('admin/gallery/category_form', [
            'title'      => 'Edit Gallery Category',
            'breadcrumb' => ['Gallery', 'Edit Category'],
            'category'   => $category,
            'action'     => site_url('admin/gallery/categories/' . $id),
        ]);
    }

    public function updateCategory(int $id)
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori gallery sudah digunakan.');
        }

        (new GalleryCategoryModel())->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/gallery'))->with('success', 'Kategori gallery berhasil diperbarui.');
    }

    public function deleteCategory(int $id)
    {
        if ((new GalleryModel())->where('category_id', $id)->countAllResults() > 0) {
            return redirect()->to(site_url('admin/gallery'))->with('error', 'Kategori masih digunakan oleh gallery.');
        }

        (new GalleryCategoryModel())->delete($id);

        return redirect()->to(site_url('admin/gallery'))->with('success', 'Kategori gallery berhasil dihapus.');
    }

    private function rules(bool $isCreate): array
    {
        return [
            'title'       => 'required|max_length[180]',
            'category_id' => 'permit_empty|is_natural_no_zero',
            'date'        => 'permit_empty|valid_date[Y-m-d]',
            'sort_order'  => 'permit_empty|integer',
            'status'      => 'required|in_list[draft,published,archived]',
            'image'       => $isCreate ? 'uploaded[image]' : 'permit_empty',
        ];
    }

    private function payload(): array
    {
        return [
            'category_id' => $this->request->getPost('category_id') ? (int) $this->request->getPost('category_id') : null,
            'title'       => trim((string) $this->request->getPost('title')),
            'description' => trim((string) $this->request->getPost('description')),
            'date'        => $this->request->getPost('date') ?: null,
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'      => (string) $this->request->getPost('status'),
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

    private function categorySlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new GalleryCategoryModel())->withDeleted()->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }
}
