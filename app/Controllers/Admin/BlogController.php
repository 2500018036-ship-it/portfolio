<?php

namespace App\Controllers\Admin;

use App\Models\BlogCategoryModel;
use App\Models\BlogPostModel;
use App\Models\BlogPostTagModel;
use App\Models\BlogTagModel;
use RuntimeException;

class BlogController extends AdminController
{
    public function index()
    {
        return view('admin/blog/index', [
            'title'      => 'Blog',
            'breadcrumb' => ['Blog'],
            'posts'      => $this->postsQuery()->findAll(),
            'categories' => (new BlogCategoryModel())->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
            'tags'       => (new BlogTagModel())->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('admin/blog/form', [
            'title'       => 'Create Blog Post',
            'breadcrumb'  => ['Blog', 'Create'],
            'post'        => null,
            'categories'  => (new BlogCategoryModel())->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'tags'        => (new BlogTagModel())->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'selectedTagIds' => [],
            'action'      => site_url('admin/blog'),
        ]);
    }

    public function create()
    {
        if (! $this->validate($this->postRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = $this->postPayload();
            $image = $this->storeUploadedFile('featured_image', 'blog', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($this->postSlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug blog sudah digunakan.');
        }

        if ($image) {
            $data['featured_image'] = $image;
            $data['thumbnail'] = $image;
        }

        $postId = (new BlogPostModel())->skipValidation(true)->insert($data);
        $this->syncPostTags((int) $postId, $this->request->getPost('tag_ids') ?? []);

        return redirect()->to(site_url('admin/blog'))->with('success', 'Blog post berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $post = (new BlogPostModel())->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Blog post tidak ditemukan.');
        }

        return view('admin/blog/form', [
            'title'       => 'Edit Blog Post',
            'breadcrumb'  => ['Blog', 'Edit'],
            'post'        => $post,
            'categories'  => (new BlogCategoryModel())->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'tags'        => (new BlogTagModel())->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'selectedTagIds' => $this->selectedTagIds($id),
            'action'      => site_url('admin/blog/' . $id),
        ]);
    }

    public function update(int $id)
    {
        $model = new BlogPostModel();
        $post = $model->find($id);

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Blog post tidak ditemukan.');
        }

        if (! $this->validate($this->postRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = $this->postPayload();
            $image = $this->storeUploadedFile('featured_image', 'blog', ['jpg', 'jpeg', 'png', 'webp'], 4096);
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        if ($this->postSlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug blog sudah digunakan.');
        }

        if ($image) {
            $this->removePublicUpload($post['featured_image'] ?: ($post['thumbnail'] ?? null));
            $data['featured_image'] = $image;
            $data['thumbnail'] = $image;
        }

        $model->skipValidation(true)->update($id, $data);
        $this->syncPostTags($id, $this->request->getPost('tag_ids') ?? []);

        return redirect()->to(site_url('admin/blog'))->with('success', 'Blog post berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $post = (new BlogPostModel())->find($id);

        if ($post) {
            $this->removePublicUpload($post['featured_image'] ?: ($post['thumbnail'] ?? null));
            (new BlogPostTagModel())->where('blog_post_id', $id)->delete();
            (new BlogPostModel())->delete($id);
        }

        return redirect()->to(site_url('admin/blog'))->with('success', 'Blog post berhasil dihapus.');
    }

    public function newCategory()
    {
        return view('admin/blog/category_form', [
            'title'      => 'Create Blog Category',
            'breadcrumb' => ['Blog', 'Create Category'],
            'category'   => null,
            'action'     => site_url('admin/blog/categories'),
        ]);
    }

    public function createCategory()
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori blog sudah digunakan.');
        }

        (new BlogCategoryModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/blog'))->with('success', 'Kategori blog berhasil dibuat.');
    }

    public function editCategory(int $id)
    {
        $category = (new BlogCategoryModel())->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori blog tidak ditemukan.');
        }

        return view('admin/blog/category_form', [
            'title'      => 'Edit Blog Category',
            'breadcrumb' => ['Blog', 'Edit Category'],
            'category'   => $category,
            'action'     => site_url('admin/blog/categories/' . $id),
        ]);
    }

    public function updateCategory(int $id)
    {
        if (! $this->validate($this->categoryRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->categoryPayload();

        if ($this->categorySlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug kategori blog sudah digunakan.');
        }

        (new BlogCategoryModel())->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/blog'))->with('success', 'Kategori blog berhasil diperbarui.');
    }

    public function deleteCategory(int $id)
    {
        if ((new BlogPostModel())->where('category_id', $id)->countAllResults() > 0) {
            return redirect()->to(site_url('admin/blog'))->with('error', 'Kategori masih digunakan oleh blog post.');
        }

        (new BlogCategoryModel())->delete($id);

        return redirect()->to(site_url('admin/blog'))->with('success', 'Kategori blog berhasil dihapus.');
    }

    public function newTag()
    {
        return view('admin/blog/tag_form', [
            'title'      => 'Create Blog Tag',
            'breadcrumb' => ['Blog', 'Create Tag'],
            'tag'        => null,
            'action'     => site_url('admin/blog/tags'),
        ]);
    }

    public function createTag()
    {
        if (! $this->validate($this->tagRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->tagPayload();

        if ($this->tagSlugExists($data['slug'])) {
            return redirect()->back()->withInput()->with('error', 'Slug tag sudah digunakan.');
        }

        (new BlogTagModel())->skipValidation(true)->insert($data);

        return redirect()->to(site_url('admin/blog'))->with('success', 'Tag blog berhasil dibuat.');
    }

    public function editTag(int $id)
    {
        $tag = (new BlogTagModel())->find($id);

        if (! $tag) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tag blog tidak ditemukan.');
        }

        return view('admin/blog/tag_form', [
            'title'      => 'Edit Blog Tag',
            'breadcrumb' => ['Blog', 'Edit Tag'],
            'tag'        => $tag,
            'action'     => site_url('admin/blog/tags/' . $id),
        ]);
    }

    public function updateTag(int $id)
    {
        if (! $this->validate($this->tagRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->tagPayload();

        if ($this->tagSlugExists($data['slug'], $id)) {
            return redirect()->back()->withInput()->with('error', 'Slug tag sudah digunakan.');
        }

        (new BlogTagModel())->skipValidation(true)->update($id, $data);

        return redirect()->to(site_url('admin/blog'))->with('success', 'Tag blog berhasil diperbarui.');
    }

    public function deleteTag(int $id)
    {
        if ((new BlogPostTagModel())->where('blog_tag_id', $id)->countAllResults() > 0) {
            return redirect()->to(site_url('admin/blog'))->with('error', 'Tag masih digunakan oleh blog post.');
        }

        (new BlogTagModel())->delete($id);

        return redirect()->to(site_url('admin/blog'))->with('success', 'Tag blog berhasil dihapus.');
    }

    private function postsQuery(): BlogPostModel
    {
        return (new BlogPostModel())
            ->select('blog_posts.*, blog_categories.name AS category_name')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->orderBy('blog_posts.sort_order', 'ASC')
            ->orderBy('blog_posts.published_at', 'DESC')
            ->orderBy('blog_posts.updated_at', 'DESC');
    }

    private function postRules(): array
    {
        return [
            'title'        => 'required|max_length[180]',
            'slug'         => 'permit_empty|max_length[200]',
            'category_id'  => 'permit_empty|is_natural_no_zero',
            'excerpt'      => 'permit_empty|max_length[500]',
            'author'       => 'permit_empty|max_length[120]',
            'status'       => 'required|in_list[draft,published,archived]',
            'published_at' => 'permit_empty|max_length[25]',
            'sort_order'   => 'permit_empty|integer',
        ];
    }

    private function postPayload(): array
    {
        $title = trim((string) $this->request->getPost('title'));
        $status = (string) $this->request->getPost('status');

        return [
            'category_id'  => $this->request->getPost('category_id') ? (int) $this->request->getPost('category_id') : null,
            'title'        => $title,
            'slug'         => $this->slugFromInput($title, $this->request->getPost('slug')),
            'excerpt'      => trim((string) $this->request->getPost('excerpt')),
            'content'      => $this->sanitizeContentHtml($this->request->getPost('content')),
            'author'       => trim((string) $this->request->getPost('author')),
            'status'       => $status,
            'published_at' => $this->publishedAt($this->request->getPost('published_at'), $status),
            'sort_order'   => (int) ($this->request->getPost('sort_order') ?: 0),
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

    private function tagRules(): array
    {
        return [
            'name'   => 'required|max_length[100]',
            'slug'   => 'permit_empty|max_length[120]',
            'status' => 'required|in_list[active,inactive]',
        ];
    }

    private function tagPayload(): array
    {
        $name = trim((string) $this->request->getPost('name'));

        return [
            'name'   => $name,
            'slug'   => $this->slugFromInput($name, $this->request->getPost('slug')),
            'status' => (string) $this->request->getPost('status'),
        ];
    }

    private function selectedTagIds(int $postId): array
    {
        return array_map(
            static fn (array $row): int => (int) $row['blog_tag_id'],
            (new BlogPostTagModel())->where('blog_post_id', $postId)->findAll()
        );
    }

    private function syncPostTags(int $postId, array $tagIds): void
    {
        $tagIds = array_values(array_unique(array_filter(array_map('intval', $tagIds), static fn (int $id): bool => $id > 0)));
        $model = new BlogPostTagModel();
        $model->where('blog_post_id', $postId)->delete();

        foreach ($tagIds as $tagId) {
            $model->skipValidation(true)->insert([
                'blog_post_id' => $postId,
                'blog_tag_id'  => $tagId,
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        }
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

    private function postSlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new BlogPostModel())->withDeleted()->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }

    private function categorySlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new BlogCategoryModel())->withDeleted()->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }

    private function tagSlugExists(string $slug, ?int $ignoreId = null): bool
    {
        $builder = (new BlogTagModel())->withDeleted()->where('slug', $slug);

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }

        return (bool) $builder->first();
    }
}
