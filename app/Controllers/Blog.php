<?php

namespace App\Controllers;

use App\Models\BlogCategoryModel;
use App\Models\BlogPostModel;
use App\Models\BlogPostTagModel;
use App\Models\BlogTagModel;
use App\Models\DocumentationModel;
use App\Models\LabModel;
use App\Models\ProjectModel;

class Blog extends BaseController
{
    public function index(): string
    {
        $query = trim((string) $this->request->getGet('q'));
        $category = trim((string) $this->request->getGet('category'));
        $model = (new BlogPostModel())
            ->select('blog_posts.*, blog_categories.name AS category_name, blog_categories.slug AS category_slug')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.status', 'published');

        if ($query !== '') {
            $model->groupStart()
                ->like('blog_posts.title', $query)
                ->orLike('blog_posts.excerpt', $query)
                ->orLike('blog_posts.content', $query)
                ->orLike('blog_categories.name', $query)
                ->groupEnd();
        }

        if ($category !== '') {
            $model->where('blog_categories.slug', $category);
        }

        $posts = $model
            ->orderBy('blog_posts.sort_order', 'ASC')
            ->orderBy('blog_posts.published_at', 'DESC')
            ->limit(24)
            ->findAll();

        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];

        return view('public/blog/index', array_merge($viewDefaults, [
            'title'           => 'Blog | ' . $siteTitle,
            'metaDescription' => 'Technical articles, notes, and portfolio updates.',
            'canonicalUrl'    => site_url('blog'),
            'siteTitle'       => $siteTitle,
            'posts'           => $posts,
            'categories'      => (new BlogCategoryModel())->where('status', 'active')->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
            'tagsByPost'      => $this->tagsByPost(array_column($posts, 'id')),
            'query'           => $query,
            'category'        => $category,
        ]));
    }

    public function show(string $slug): string
    {
        $post = (new BlogPostModel())
            ->select('blog_posts.*, blog_categories.name AS category_name, blog_categories.slug AS category_slug')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.slug', $slug)
            ->where('blog_posts.status', 'published')
            ->first();

        if (! $post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Blog post not found.');
        }

        $this->trackView((int) $post['id']);
        $post['views_count'] = (int) ($post['views_count'] ?? 0) + 1;

        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];
        $ogImage = $this->publicAssetUrl($post['featured_image'] ?: ($post['thumbnail'] ?? null), $viewDefaults['defaultOgImage']);

        return view('public/blog/show', array_merge($viewDefaults, [
            'title'           => $post['title'] . ' | ' . $siteTitle,
            'metaDescription' => $post['excerpt'] ?: 'Blog post detail.',
            'canonicalUrl'    => site_url('blog/' . $post['slug']),
            'ogImage'         => $ogImage,
            'siteTitle'       => $siteTitle,
            'post'            => $post,
            'tags'            => $this->tagsForPost((int) $post['id']),
            'relatedPosts'    => $this->relatedPosts($post),
            'relatedDocs'     => $this->relatedDocs(),
            'relatedProjects' => $this->relatedProjects(),
            'relatedLabs'     => $this->relatedLabs(),
        ]));
    }

    private function tagsByPost(array $postIds): array
    {
        $postIds = array_values(array_filter(array_map('intval', $postIds)));

        if ($postIds === []) {
            return [];
        }

        $rows = (new BlogPostTagModel())
            ->select('blog_post_tags.blog_post_id, blog_tags.name, blog_tags.slug')
            ->join('blog_tags', 'blog_tags.id = blog_post_tags.blog_tag_id')
            ->whereIn('blog_post_tags.blog_post_id', $postIds)
            ->where('blog_tags.status', 'active')
            ->findAll();

        $tags = [];

        foreach ($rows as $row) {
            $tags[(int) $row['blog_post_id']][] = $row;
        }

        return $tags;
    }

    private function tagsForPost(int $postId): array
    {
        return (new BlogPostTagModel())
            ->select('blog_tags.name, blog_tags.slug')
            ->join('blog_tags', 'blog_tags.id = blog_post_tags.blog_tag_id')
            ->where('blog_post_tags.blog_post_id', $postId)
            ->where('blog_tags.status', 'active')
            ->findAll();
    }

    private function relatedPosts(array $post): array
    {
        $model = (new BlogPostModel())
            ->select('blog_posts.*, blog_categories.name AS category_name')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.id !=', $post['id'])
            ->where('blog_posts.status', 'published');

        if ($post['category_id']) {
            $model->where('blog_posts.category_id', $post['category_id']);
        }

        return $model->orderBy('blog_posts.published_at', 'DESC')->limit(3)->findAll();
    }

    private function trackView(int $postId): void
    {
        if (! db_connect()->fieldExists('views_count', 'blog_posts')) {
            return;
        }

        db_connect()
            ->table('blog_posts')
            ->where('id', $postId)
            ->set('views_count', 'views_count + 1', false)
            ->update();
    }

    private function relatedDocs(): array
    {
        return (new DocumentationModel())
            ->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->findAll();
    }

    private function relatedProjects(): array
    {
        return (new ProjectModel())
            ->where('status', 'published')
            ->where('is_featured', 1)
            ->orderBy('sort_order', 'ASC')
            ->limit(3)
            ->findAll();
    }

    private function relatedLabs(): array
    {
        return (new LabModel())
            ->where('status', 'published')
            ->where('is_featured', 1)
            ->orderBy('sort_order', 'ASC')
            ->limit(3)
            ->findAll();
    }

}
