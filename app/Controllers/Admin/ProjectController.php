<?php

namespace App\Controllers\Admin;

use App\Models\ProjectCategoryModel;
use App\Models\ProjectImageModel;
use App\Models\ProjectModel;
use RuntimeException;

class ProjectController extends AdminController
{
    public function index()
    {
        return view('admin/projects/index', [
            'title'      => 'Projects',
            'breadcrumb' => ['Projects'],
            'projects'   => (new ProjectModel())
                ->select('projects.*, project_categories.name AS category_name')
                ->join('project_categories', 'project_categories.id = projects.category_id', 'left')
                ->orderBy('projects.sort_order', 'ASC')
                ->orderBy('projects.created_at', 'DESC')
                ->findAll(),
            'categories' => (new ProjectCategoryModel())->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $project = (new ProjectModel())
            ->select('projects.*, project_categories.name AS category_name')
            ->join('project_categories', 'project_categories.id = projects.category_id', 'left')
            ->find($id);

        if (! $project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Project tidak ditemukan.');
        }

        return view('admin/projects/show', [
            'title'      => 'Project Detail',
            'breadcrumb' => ['Projects', 'Detail'],
            'project'    => $project,
            'images'     => (new ProjectImageModel())
                ->where('project_id', $id)
                ->orderBy('is_featured', 'DESC')
                ->orderBy('sort_order', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->findAll(),
        ]);
    }

    public function new()
    {
        return view('admin/projects/form', [
            'title'      => 'Create Project',
            'breadcrumb' => ['Projects', 'Create'],
            'project'    => null,
            'categories' => (new ProjectCategoryModel())->orderBy('name', 'ASC')->findAll(),
            'action'     => site_url('admin/projects'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->projectRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->projectPayload();

        if ($this->projectSlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug project sudah digunakan.');
        }

        try {
            $thumbnail = $this->storeUploadedFile('thumbnail', 'projects', ['jpg', 'jpeg', 'png', 'webp'], 3072);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($thumbnail) {
            $data['thumbnail'] = $thumbnail;
        }

        (new ProjectModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/projects'))->with('success', 'Project berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $project = (new ProjectModel())->find($id);

        if (! $project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Project tidak ditemukan.');
        }

        return view('admin/projects/form', [
            'title'      => 'Edit Project',
            'breadcrumb' => ['Projects', 'Edit'],
            'project'    => $project,
            'categories' => (new ProjectCategoryModel())->orderBy('name', 'ASC')->findAll(),
            'action'     => site_url('admin/projects/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new ProjectModel();
        $project = $model->find($id);

        if (! $project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Project tidak ditemukan.');
        }

        if (! $this->validate($this->projectRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->projectPayload($project);

        if ($this->projectSlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug project sudah digunakan.');
        }

        try {
            $thumbnail = $this->storeUploadedFile('thumbnail', 'projects', ['jpg', 'jpeg', 'png', 'webp'], 3072);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($thumbnail) {
            if (! $this->projectImagePathExists($id, $project['thumbnail'] ?? null)) {
                $this->removePublicUpload($project['thumbnail'] ?? null);
            }

            $data['thumbnail'] = $thumbnail;
        }

        $model->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/projects'))->with('success', 'Project berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $project = (new ProjectModel())->find($id);

        if ($project) {
            $images = (new ProjectImageModel())->where('project_id', $id)->findAll();
            $paths = [$project['thumbnail'] ?? null];

            foreach ($images as $image) {
                $paths[] = $image['image'] ?? null;
            }

            foreach (array_unique(array_filter($paths)) as $path) {
                $this->removePublicUpload($path);
            }

            (new ProjectImageModel())->where('project_id', $id)->delete();
            (new ProjectModel())->delete($id);
        }

        return redirect()->to(site_url('admin/projects'))->with('success', 'Project berhasil dihapus.');
    }

    public function uploadImages(int $projectId)
    {
        $project = $this->findProject($projectId);

        if (! $this->validate($this->imageRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $paths = $this->storeUploadedFiles('project_images', 'projects/screenshots', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($paths === []) {
            return redirect()->back()->with('error', 'Pilih minimal satu gambar project.');
        }

        $imageModel = new ProjectImageModel();
        $caption = trim((string) $this->request->getPost('caption'));
        $sortOrder = (int) ($this->request->getPost('sort_order') ?: 0);
        $makeFeatured = (bool) $this->request->getPost('is_featured');
        $featuredPath = null;

        if ($makeFeatured) {
            $imageModel->where('project_id', $projectId)->set(['is_featured' => 0])->update();
        }

        foreach ($paths as $index => $path) {
            $isFeatured = $makeFeatured && $index === 0 ? 1 : 0;

            $imageModel->skipValidation(true)->insert([
                'project_id'   => $projectId,
                'image'        => $path,
                'caption'      => $caption,
                'sort_order'   => $sortOrder + $index,
                'is_featured'  => $isFeatured,
                'created_at'   => date('Y-m-d H:i:s'),
            ]);

            if ($isFeatured) {
                $featuredPath = $path;
            }
        }

        if ($featuredPath) {
            (new ProjectModel())->skipValidation(true)->update($projectId, ['thumbnail' => $featuredPath]);
        } elseif (! ($project['thumbnail'] ?? null)) {
            (new ProjectModel())->skipValidation(true)->update($projectId, ['thumbnail' => $paths[0]]);
        }

        return redirect()->to(site_url('admin/projects/' . $projectId))->with('success', 'Gambar project berhasil diupload.');
    }

    public function updateImage(int $projectId, int $imageId)
    {
        $this->findProject($projectId);
        $image = $this->findProjectImage($projectId, $imageId);

        if (! $this->validate($this->imageRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageModel = new ProjectImageModel();
        $isFeatured = (bool) $this->request->getPost('is_featured');

        if ($isFeatured) {
            $imageModel->where('project_id', $projectId)->where('id !=', $imageId)->set(['is_featured' => 0])->update();
            (new ProjectModel())->skipValidation(true)->update($projectId, ['thumbnail' => $image['image']]);
        } elseif ((int) ($image['is_featured'] ?? 0) === 1) {
            (new ProjectModel())->skipValidation(true)->update($projectId, ['thumbnail' => null]);
        }

        $imageModel->skipValidation(true)->update($imageId, [
            'caption'     => trim((string) $this->request->getPost('caption')),
            'sort_order'  => (int) ($this->request->getPost('sort_order') ?: 0),
            'is_featured' => $isFeatured ? 1 : 0,
        ]);

        return redirect()->to(site_url('admin/projects/' . $projectId))->with('success', 'Gambar project berhasil diperbarui.');
    }

    public function deleteImage(int $projectId, int $imageId)
    {
        $project = $this->findProject($projectId);
        $image = $this->findProjectImage($projectId, $imageId);

        (new ProjectImageModel())->delete($imageId);
        $this->removePublicUpload($image['image']);

        if (($project['thumbnail'] ?? null) === $image['image'] || (int) ($image['is_featured'] ?? 0) === 1) {
            $replacement = (new ProjectImageModel())
                ->where('project_id', $projectId)
                ->orderBy('is_featured', 'DESC')
                ->orderBy('sort_order', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->first();

            (new ProjectModel())->skipValidation(true)->update($projectId, [
                'thumbnail' => $replacement['image'] ?? null,
            ]);

            if ($replacement) {
                (new ProjectImageModel())->skipValidation(true)->update($replacement['id'], ['is_featured' => 1]);
            }
        }

        return redirect()->to(site_url('admin/projects/' . $projectId))->with('success', 'Gambar project berhasil dihapus.');
    }

    public function newCategory()
    {
        return view('admin/projects/category_form', [
            'title'      => 'Create Project Category',
            'breadcrumb' => ['Projects', 'Create Category'],
            'category'   => null,
            'action'     => site_url('admin/projects/categories'),
        ]);
    }

    public function createCategory()
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori sudah digunakan.');
        }

        (new ProjectCategoryModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/projects'))->with('success', 'Kategori project berhasil dibuat.');
    }

    public function editCategory(int $id)
    {
        $category = (new ProjectCategoryModel())->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori project tidak ditemukan.');
        }

        return view('admin/projects/category_form', [
            'title'      => 'Edit Project Category',
            'breadcrumb' => ['Projects', 'Edit Category'],
            'category'   => $category,
            'action'     => site_url('admin/projects/categories/' . $id),
        ]);
    }

    public function updateCategory(int $id)
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori sudah digunakan.');
        }

        (new ProjectCategoryModel())->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/projects'))->with('success', 'Kategori project berhasil diperbarui.');
    }

    public function deleteCategory(int $id)
    {
        $count = (new ProjectModel())->where('category_id', $id)->countAllResults();

        if ($count > 0) {
            return redirect()->to(site_url('admin/projects'))->with('error', 'Kategori masih digunakan oleh project.');
        }

        (new ProjectCategoryModel())->delete($id);

        return redirect()->to(site_url('admin/projects'))->with('success', 'Kategori project berhasil dihapus.');
    }

    private function projectRules(): array
    {
        return [
            'title'             => 'required|max_length[180]',
            'slug'              => 'permit_empty|max_length[200]',
            'category_id'       => 'permit_empty|is_natural_no_zero',
            'demo_url'          => 'permit_empty|valid_url|max_length[255]',
            'github_url'        => 'permit_empty|valid_url|max_length[255]',
            'documentation_url' => 'permit_empty|valid_url|max_length[255]',
            'start_date'        => 'permit_empty|valid_date[Y-m-d]',
            'end_date'          => 'permit_empty|valid_date[Y-m-d]',
            'sort_order'        => 'permit_empty|integer',
            'status'            => 'required|in_list[draft,published,archived]',
        ];
    }

    private function projectPayload(?array $existing = null): array
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
            'description'       => trim((string) $this->request->getPost('description')),
            'problem'           => trim((string) $this->request->getPost('problem')),
            'solution'          => trim((string) $this->request->getPost('solution')),
            'architecture'      => trim((string) $this->request->getPost('architecture')),
            'technologies'      => trim((string) $this->request->getPost('technologies')),
            'features'          => trim((string) $this->request->getPost('features')),
            'challenges'        => trim((string) $this->request->getPost('challenges')),
            'result'            => trim((string) $this->request->getPost('result')),
            'lessons_learned'   => trim((string) $this->request->getPost('lessons_learned')),
            'github_url'        => trim((string) $this->request->getPost('github_url')),
            'demo_url'          => trim((string) $this->request->getPost('demo_url')),
            'documentation_url' => trim((string) $this->request->getPost('documentation_url')),
            'start_date'        => $this->request->getPost('start_date') ?: null,
            'end_date'          => $this->request->getPost('end_date') ?: null,
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

    private function projectSlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new ProjectModel())->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }

    private function categorySlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new ProjectCategoryModel())->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }

    private function findProject(int $id): array
    {
        $project = (new ProjectModel())->find($id);

        if (! $project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Project tidak ditemukan.');
        }

        return $project;
    }

    private function findProjectImage(int $projectId, int $imageId): array
    {
        $image = (new ProjectImageModel())
            ->where('project_id', $projectId)
            ->where('id', $imageId)
            ->first();

        if (! $image) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Gambar project tidak ditemukan.');
        }

        return $image;
    }

    private function projectImagePathExists(int $projectId, ?string $path): bool
    {
        if (! $path) {
            return false;
        }

        return (bool) (new ProjectImageModel())
            ->where('project_id', $projectId)
            ->where('image', $path)
            ->first();
    }
}
