<?php

namespace App\Controllers\Admin;

use App\Models\LabCategoryModel;
use App\Models\LabBlockModel;
use App\Models\LabImageModel;
use App\Models\LabModel;
use RuntimeException;

class LabController extends AdminController
{
    public function index()
    {
        return view('admin/labs/index', [
            'title'      => 'Networking Labs',
            'breadcrumb' => ['Networking Labs'],
            'labs'       => (new LabModel())
                ->select('labs.*, lab_categories.name AS category_name')
                ->join('lab_categories', 'lab_categories.id = labs.category_id', 'left')
                ->orderBy('labs.sort_order', 'ASC')
                ->orderBy('labs.created_at', 'DESC')
                ->findAll(),
            'categories' => (new LabCategoryModel())->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $lab = (new LabModel())
            ->select('labs.*, lab_categories.name AS category_name')
            ->join('lab_categories', 'lab_categories.id = labs.category_id', 'left')
            ->find($id);

        if (! $lab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lab tidak ditemukan.');
        }

        return view('admin/labs/show', [
            'title'      => 'Lab Detail',
            'breadcrumb' => ['Networking Labs', 'Detail'],
            'lab'        => $lab,
            'images'     => (new LabImageModel())
                ->where('lab_id', $id)
                ->orderBy('is_featured', 'DESC')
                ->orderBy('sort_order', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->findAll(),
            'blocks'     => (new LabBlockModel())
                ->where('lab_id', $id)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->findAll(),
        ]);
    }

    public function new()
    {
        return view('admin/labs/form', [
            'title'      => 'Create Lab',
            'breadcrumb' => ['Networking Labs', 'Create'],
            'lab'        => null,
            'categories' => (new LabCategoryModel())->orderBy('name', 'ASC')->findAll(),
            'action'     => site_url('admin/labs'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->labRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentialErrors = $this->credentialErrors();

        if ($credentialErrors !== []) {
            return redirect()->back()->withInput()->with('errors', $credentialErrors);
        }

        $data = $this->labPayload();

        if ($this->labSlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug lab sudah digunakan.');
        }

        try {
            $thumbnail = $this->storeUploadedFile('thumbnail', 'labs', ['jpg', 'jpeg', 'png', 'webp'], 3072);
            $topologyImage = $this->storeUploadedFile('topology_image', 'labs/topologies', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($thumbnail) {
            $data['thumbnail'] = $thumbnail;
        }

        if ($topologyImage) {
            $data['topology_image'] = $topologyImage;
        }

        $id = (new LabModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/labs/' . $id))->with('success', 'Lab berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $lab = (new LabModel())->find($id);

        if (! $lab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lab tidak ditemukan.');
        }

        return view('admin/labs/form', [
            'title'      => 'Edit Lab',
            'breadcrumb' => ['Networking Labs', 'Edit'],
            'lab'        => $lab,
            'categories' => (new LabCategoryModel())->orderBy('name', 'ASC')->findAll(),
            'action'     => site_url('admin/labs/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new LabModel();
        $lab = $model->find($id);

        if (! $lab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lab tidak ditemukan.');
        }

        if (! $this->validate($this->labRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentialErrors = $this->credentialErrors();

        if ($credentialErrors !== []) {
            return redirect()->back()->withInput()->with('errors', $credentialErrors);
        }

        $data = $this->labPayload($lab);

        if ($this->labSlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug lab sudah digunakan.');
        }

        try {
            $thumbnail = $this->storeUploadedFile('thumbnail', 'labs', ['jpg', 'jpeg', 'png', 'webp'], 3072);
            $topologyImage = $this->storeUploadedFile('topology_image', 'labs/topologies', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($thumbnail) {
            if (! $this->labImagePathExists($id, $lab['thumbnail'] ?? null)) {
                $this->removePublicUpload($lab['thumbnail'] ?? null);
            }

            $data['thumbnail'] = $thumbnail;
        }

        if ($topologyImage) {
            $this->removePublicUpload($lab['topology_image'] ?? null);
            $data['topology_image'] = $topologyImage;
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/labs/' . $id))->with('success', 'Lab berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $lab = (new LabModel())->find($id);

        if ($lab) {
            $images = (new LabImageModel())->where('lab_id', $id)->findAll();
            $blocks = (new LabBlockModel())->where('lab_id', $id)->findAll();
            $paths = [
                $lab['thumbnail'] ?? null,
                $lab['topology_image'] ?? null,
            ];

            foreach ($images as $image) {
                $paths[] = $image['image'] ?? null;
            }

            foreach ($blocks as $block) {
                $paths[] = $block['file_path'] ?? null;
            }

            foreach (array_unique(array_filter($paths)) as $path) {
                $this->removePublicUpload($path);
            }

            (new LabImageModel())->where('lab_id', $id)->delete();
            (new LabBlockModel())->where('lab_id', $id)->delete();
            (new LabModel())->delete($id);
        }

        return redirect()->to(site_url('admin/labs'))->with('success', 'Lab berhasil dihapus.');
    }

    public function createBlock(int $labId)
    {
        $this->findLab($labId);

        if (! $this->validate($this->blockRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentialError = $this->credentialErrorForValue((string) $this->request->getPost('content'), 'content');

        if ($credentialError !== null) {
            return redirect()->back()->withInput()->with('errors', $credentialError);
        }

        $data = $this->blockPayload($labId);
        $uploaded = null;

        try {
            $uploaded = $this->storeBlockUpload($data['block_type'], true);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($uploaded) {
            $data['file_path'] = $uploaded['path'];
            $data['file_original_name'] = $uploaded['name'];
        }

        (new LabBlockModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/labs/' . $labId))->with('success', 'Custom lab block berhasil ditambahkan.');
    }

    public function updateBlock(int $labId, int $blockId)
    {
        $this->findLab($labId);
        $block = $this->findLabBlock($labId, $blockId);

        if (! $this->validate($this->blockRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentialError = $this->credentialErrorForValue((string) $this->request->getPost('content'), 'content');

        if ($credentialError !== null) {
            return redirect()->back()->withInput()->with('errors', $credentialError);
        }

        $data = $this->blockPayload($labId);
        $fileType = in_array($data['block_type'], ['image', 'file'], true);

        try {
            $uploaded = $this->storeBlockUpload($data['block_type'], false);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($uploaded) {
            $this->removePublicUpload($block['file_path'] ?? null);
            $data['file_path'] = $uploaded['path'];
            $data['file_original_name'] = $uploaded['name'];
        } elseif (! $fileType && ($block['file_path'] ?? null)) {
            $this->removePublicUpload($block['file_path']);
            $data['file_path'] = null;
            $data['file_original_name'] = null;
        } elseif ($fileType && ! ($block['file_path'] ?? null)) {
            return redirect()->back()->withInput()->with('error', 'Upload file wajib diisi untuk block image/file.');
        }

        (new LabBlockModel())->skipValidation(true)->update($blockId, $data);

        return redirect()->to(site_url('admin/labs/' . $labId))->with('success', 'Custom lab block berhasil diperbarui.');
    }

    public function deleteBlock(int $labId, int $blockId)
    {
        $this->findLab($labId);
        $block = $this->findLabBlock($labId, $blockId);

        (new LabBlockModel())->delete($blockId);
        $this->removePublicUpload($block['file_path'] ?? null);

        return redirect()->to(site_url('admin/labs/' . $labId))->with('success', 'Custom lab block berhasil dihapus.');
    }

    public function uploadImages(int $labId)
    {
        $lab = $this->findLab($labId);

        if (! $this->validate($this->imageRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $paths = $this->storeUploadedFiles('lab_images', 'labs/images', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($paths === []) {
            return redirect()->back()->with('error', 'Pilih minimal satu gambar lab.');
        }

        $imageModel = new LabImageModel();
        $caption = trim((string) $this->request->getPost('caption'));
        $sortOrder = (int) ($this->request->getPost('sort_order') ?: 0);
        $makeFeatured = (bool) $this->request->getPost('is_featured');
        $featuredPath = null;

        if ($makeFeatured) {
            $imageModel->where('lab_id', $labId)->set(['is_featured' => 0])->update();
        }

        foreach ($paths as $index => $path) {
            $isFeatured = $makeFeatured && $index === 0 ? 1 : 0;

            $imageModel->skipValidation(true)->insert([
                'lab_id'      => $labId,
                'image'       => $path,
                'caption'     => $caption,
                'sort_order'  => $sortOrder + $index,
                'is_featured' => $isFeatured,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);

            if ($isFeatured) {
                $featuredPath = $path;
            }
        }

        if ($featuredPath) {
            (new LabModel())->skipValidation(true)->update($labId, ['thumbnail' => $featuredPath]);
        } elseif (! ($lab['thumbnail'] ?? null)) {
            (new LabModel())->skipValidation(true)->update($labId, ['thumbnail' => $paths[0]]);
        }

        return redirect()->to(site_url('admin/labs/' . $labId))->with('success', 'Gambar lab berhasil diupload.');
    }

    public function updateImage(int $labId, int $imageId)
    {
        $this->findLab($labId);
        $image = $this->findLabImage($labId, $imageId);

        if (! $this->validate($this->imageRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageModel = new LabImageModel();
        $isFeatured = (bool) $this->request->getPost('is_featured');

        if ($isFeatured) {
            $imageModel->where('lab_id', $labId)->where('id !=', $imageId)->set(['is_featured' => 0])->update();
            (new LabModel())->skipValidation(true)->update($labId, ['thumbnail' => $image['image']]);
        } elseif ((int) ($image['is_featured'] ?? 0) === 1) {
            (new LabModel())->skipValidation(true)->update($labId, ['thumbnail' => null]);
        }

        $imageModel->skipValidation(true)->update($imageId, [
            'caption'     => trim((string) $this->request->getPost('caption')),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?: 0),
            'is_featured' => $isFeatured ? 1 : 0,
        ]);

        return redirect()->to(site_url('admin/labs/' . $labId))->with('success', 'Gambar lab berhasil diperbarui.');
    }

    public function deleteImage(int $labId, int $imageId)
    {
        $lab = $this->findLab($labId);
        $image = $this->findLabImage($labId, $imageId);

        (new LabImageModel())->delete($imageId);
        $this->removePublicUpload($image['image']);

        if (($lab['thumbnail'] ?? null) === $image['image'] || (int) ($image['is_featured'] ?? 0) === 1) {
            $replacement = (new LabImageModel())
                ->where('lab_id', $labId)
                ->orderBy('is_featured', 'DESC')
                ->orderBy('sort_order', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->first();

            (new LabModel())->skipValidation(true)->update($labId, [
                'thumbnail' => $replacement['image'] ?? null,
            ]);

            if ($replacement) {
                (new LabImageModel())->skipValidation(true)->update($replacement['id'], ['is_featured' => 1]);
            }
        }

        return redirect()->to(site_url('admin/labs/' . $labId))->with('success', 'Gambar lab berhasil dihapus.');
    }

    public function newCategory()
    {
        return view('admin/labs/category_form', [
            'title'      => 'Create Lab Category',
            'breadcrumb' => ['Networking Labs', 'Create Category'],
            'category'   => null,
            'action'     => site_url('admin/labs/categories'),
        ]);
    }

    public function createCategory()
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori lab sudah digunakan.');
        }

        (new LabCategoryModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/labs'))->with('success', 'Kategori lab berhasil dibuat.');
    }

    public function editCategory(int $id)
    {
        $category = (new LabCategoryModel())->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori lab tidak ditemukan.');
        }

        return view('admin/labs/category_form', [
            'title'      => 'Edit Lab Category',
            'breadcrumb' => ['Networking Labs', 'Edit Category'],
            'category'   => $category,
            'action'     => site_url('admin/labs/categories/' . $id),
        ]);
    }

    public function updateCategory(int $id)
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori lab sudah digunakan.');
        }

        (new LabCategoryModel())->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/labs'))->with('success', 'Kategori lab berhasil diperbarui.');
    }

    public function deleteCategory(int $id)
    {
        $count = (new LabModel())->where('category_id', $id)->countAllResults();

        if ($count > 0) {
            return redirect()->to(site_url('admin/labs'))->with('error', 'Kategori masih digunakan oleh lab.');
        }

        (new LabCategoryModel())->delete($id);

        return redirect()->to(site_url('admin/labs'))->with('success', 'Kategori lab berhasil dihapus.');
    }

    private function labRules(): array
    {
        return [
            'title'             => 'required|max_length[180]',
            'slug'              => 'permit_empty|max_length[200]',
            'category_id'       => 'permit_empty|is_natural_no_zero',
            'documentation_url' => 'permit_empty|valid_url|max_length[255]',
            'sort_order'        => 'permit_empty|integer',
            'status'            => 'required|in_list[draft,published,archived]',
        ];
    }

    private function labPayload(?array $existing = null): array
    {
        $title = trim((string) $this->request->getPost('title'));
        $status = (string) $this->request->getPost('status');
        $publishedAt = null;

        if ($status === 'published') {
            $publishedAt = $existing['published_at'] ?? date('Y-m-d H:i:s');
        }

        return [
            'category_id'       => $this->request->getPost('category_id') ? (int) $this->request->getPost('category_id') : null,
            'title'             => $title,
            'slug'              => $this->slugFromInput($title, $this->request->getPost('slug')),
            'short_description' => trim((string) $this->request->getPost('short_description')),
            'objective'         => trim((string) $this->request->getPost('objective')),
            'description'       => trim((string) $this->request->getPost('description')),
            'topology'          => trim((string) $this->request->getPost('topology')),
            'devices'           => trim((string) $this->request->getPost('devices')),
            'hardware'          => trim((string) $this->request->getPost('hardware')),
            'software'          => trim((string) $this->request->getPost('software')),
            'ip_addressing'     => trim((string) $this->request->getPost('ip_addressing')),
            'vlan'              => trim((string) $this->request->getPost('vlan')),
            'routing'           => trim((string) $this->request->getPost('routing')),
            'firewall'          => trim((string) $this->request->getPost('firewall')),
            'nat'               => trim((string) $this->request->getPost('nat')),
            'vpn'               => trim((string) $this->request->getPost('vpn')),
            'monitoring'        => trim((string) $this->request->getPost('monitoring')),
            'troubleshooting'   => trim((string) $this->request->getPost('troubleshooting')),
            'configuration'     => trim((string) $this->request->getPost('configuration')),
            'testing'           => trim((string) $this->request->getPost('testing')),
            'result'            => trim((string) $this->request->getPost('result')),
            'lessons_learned'   => trim((string) $this->request->getPost('lessons_learned')),
            'documentation_url' => trim((string) $this->request->getPost('documentation_url')),
            'status'            => $status,
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
            'sort_order'        => (int) ($this->request->getPost('sort_order') ?: 0),
            'published_at'      => $publishedAt,
        ];
    }

    private function imageRules(): array
    {
        return [
            'caption'    => 'permit_empty|max_length[255]',
            'sort_order' => 'permit_empty|integer',
        ];
    }

    private function blockRules(): array
    {
        return [
            'title'      => 'required|max_length[160]',
            'block_type' => 'required|in_list[text,code,image,file]',
            'sort_order' => 'permit_empty|integer',
            'status'     => 'required|in_list[active,inactive]',
        ];
    }

    private function blockPayload(int $labId): array
    {
        return [
            'lab_id'     => $labId,
            'title'      => trim((string) $this->request->getPost('title')),
            'block_type' => (string) $this->request->getPost('block_type'),
            'content'    => trim((string) $this->request->getPost('content')),
            'sort_order' => (int) ($this->request->getPost('sort_order') ?: 0),
            'status'     => (string) $this->request->getPost('status'),
        ];
    }

    private function storeBlockUpload(string $blockType, bool $required): ?array
    {
        $file = $this->request->getFile('block_file');
        $expectsFile = in_array($blockType, ['image', 'file'], true);

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            if ($required && $expectsFile) {
                throw new RuntimeException('Upload file wajib diisi untuk block image/file.');
            }

            return null;
        }

        if (! $expectsFile) {
            throw new RuntimeException('Upload file hanya digunakan untuk block bertipe image atau file.');
        }

        $originalName = $file->getClientName();
        $allowed = $blockType === 'image'
            ? ['jpg', 'jpeg', 'png', 'webp']
            : ['pdf', 'doc', 'docx', 'txt'];
        $maxSize = $blockType === 'image' ? 4096 : 8192;

        return [
            'path' => $this->storeSingleUploadedFile($file, 'labs/blocks', $allowed, $maxSize),
            'name' => $originalName,
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

    private function credentialErrors(): array
    {
        $fields = ['configuration', 'vpn', 'firewall', 'nat', 'troubleshooting'];

        foreach ($fields as $field) {
            $error = $this->credentialErrorForValue((string) $this->request->getPost($field), $field);

            if ($error !== null) {
                return $error;
            }
        }

        return [];
    }

    private function credentialErrorForValue(string $value, string $field): ?array
    {
        $patterns = [
            '/^\s*enable\s+secret\s+(.+)$/i',
            '/^\s*username\s+\S+\s+(?:password|secret)\s+(.+)$/i',
            '/^\s*(?:password|secret|pre-shared-key|psk)\s+(.+)$/i',
            '/^\s*snmp-server\s+community\s+(.+)$/i',
        ];
        $lines = preg_split('/\R/', $value) ?: [];

        foreach ($lines as $line) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $line, $matches) && ! $this->looksRedacted($matches[1] ?? '')) {
                    return [
                        $field => 'Dokumentasi lab tidak boleh menyimpan credential jaringan nyata. Gunakan placeholder seperti <redacted>.',
                    ];
                }
            }
        }

        return null;
    }

    private function looksRedacted(string $value): bool
    {
        $value = strtolower(trim($value, " \t\n\r\0\x0B\"'"));

        if ($value === '') {
            return true;
        }

        foreach (['redacted', '<redacted>', '[redacted]', 'changeme', 'example', 'placeholder', '<password>', '<secret>', 'xxxx', '***'] as $placeholder) {
            if (str_contains($value, $placeholder)) {
                return true;
            }
        }

        return false;
    }

    private function labSlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new LabModel())->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }

    private function categorySlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new LabCategoryModel())->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }

    private function findLab(int $id): array
    {
        $lab = (new LabModel())->find($id);

        if (! $lab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lab tidak ditemukan.');
        }

        return $lab;
    }

    private function findLabImage(int $labId, int $imageId): array
    {
        $image = (new LabImageModel())
            ->where('lab_id', $labId)
            ->where('id', $imageId)
            ->first();

        if (! $image) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Gambar lab tidak ditemukan.');
        }

        return $image;
    }

    private function findLabBlock(int $labId, int $blockId): array
    {
        $block = (new LabBlockModel())
            ->where('lab_id', $labId)
            ->where('id', $blockId)
            ->first();

        if (! $block) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Custom lab block tidak ditemukan.');
        }

        return $block;
    }

    private function labImagePathExists(int $labId, ?string $path): bool
    {
        if (! $path) {
            return false;
        }

        return (bool) (new LabImageModel())
            ->where('lab_id', $labId)
            ->where('image', $path)
            ->first();
    }
}
