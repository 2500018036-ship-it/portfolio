<?php

namespace App\Controllers;

use App\Models\DocumentationModel;
use App\Models\LabModel;
use App\Models\ProjectModel;

class Docs extends BaseController
{
    public function index(): string
    {
        $query = trim((string) $this->request->getGet('q'));
        $category = trim((string) $this->request->getGet('category'));
        $model = (new DocumentationModel())
            ->where('status', 'published');

        if ($query !== '') {
            $model->groupStart()
                ->like('title', $query)
                ->orLike('summary', $query)
                ->orLike('excerpt', $query)
                ->orLike('content', $query)
                ->orLike('tags', $query)
                ->groupEnd();
        }

        if ($category !== '') {
            $model->where('category', $category);
        }

        $docs = $model
            ->orderBy('sort_order', 'ASC')
            ->orderBy('published_at', 'DESC')
            ->limit(24)
            ->findAll();

        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];

        return view('public/docs/index', array_merge($viewDefaults, [
            'title'           => 'Technical Documentation | ' . $siteTitle,
            'metaDescription' => 'Technical documentation, notes, and implementation writeups.',
            'canonicalUrl'    => site_url('docs'),
            'siteTitle'       => $siteTitle,
            'docs'            => $docs,
            'categories'      => $this->categories(),
            'query'           => $query,
            'category'        => $category,
        ]));
    }

    public function show(string $slug): string
    {
        $document = (new DocumentationModel())
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $document) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Documentation not found.');
        }

        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];
        $ogImage = $this->publicAssetUrl($document['featured_image'] ?: ($document['thumbnail'] ?? null), $viewDefaults['defaultOgImage']);

        return view('public/docs/show', array_merge($viewDefaults, [
            'title'           => $document['title'] . ' | ' . $siteTitle,
            'metaDescription' => $document['summary'] ?: ($document['excerpt'] ?: 'Technical documentation detail.'),
            'canonicalUrl'    => site_url('docs/' . $document['slug']),
            'ogImage'         => $ogImage,
            'siteTitle'       => $siteTitle,
            'document'        => $document,
            'relatedDocs'     => $this->relatedDocs($document),
            'relatedProjects' => $this->relatedProjects(),
            'relatedLabs'     => $this->relatedLabs(),
        ]));
    }

    private function categories(): array
    {
        return (new DocumentationModel())
            ->select('category')
            ->where('status', 'published')
            ->where('category !=', '')
            ->groupBy('category')
            ->orderBy('category', 'ASC')
            ->findAll();
    }

    private function relatedDocs(array $document): array
    {
        $model = (new DocumentationModel())
            ->where('id !=', $document['id'])
            ->where('status', 'published');

        if ($document['category']) {
            $model->where('category', $document['category']);
        }

        return $model
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
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->findAll();
    }

    private function relatedLabs(): array
    {
        return (new LabModel())
            ->where('status', 'published')
            ->where('is_featured', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->findAll();
    }

}
