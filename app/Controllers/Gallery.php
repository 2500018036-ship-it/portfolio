<?php

namespace App\Controllers;

use App\Models\GalleryCategoryModel;
use App\Models\GalleryModel;

class Gallery extends BaseController
{
    public function index(): string
    {
        $category = trim((string) $this->request->getGet('category'));
        $model = (new GalleryModel())
            ->select('gallery.*, gallery_categories.name AS category_name, gallery_categories.slug AS category_slug')
            ->join('gallery_categories', 'gallery_categories.id = gallery.category_id', 'left')
            ->where('gallery.status', 'published');

        if ($category !== '') {
            $model->where('gallery_categories.slug', $category);
        }

        $items = $model
            ->orderBy('gallery.sort_order', 'ASC')
            ->orderBy('gallery.date', 'DESC')
            ->orderBy('gallery.created_at', 'DESC')
            ->findAll();
        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];

        return view('public/gallery/index', array_merge($viewDefaults, [
            'title'           => 'Gallery | ' . $siteTitle,
            'metaDescription' => 'Portfolio gallery and visual documentation.',
            'canonicalUrl'    => site_url('gallery'),
            'siteTitle'       => $siteTitle,
            'items'           => $items,
            'categories'      => (new GalleryCategoryModel())->where('status', 'active')->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
            'category'        => $category,
        ]));
    }

    public function show(int $id): string
    {
        $item = (new GalleryModel())
            ->select('gallery.*, gallery_categories.name AS category_name, gallery_categories.slug AS category_slug')
            ->join('gallery_categories', 'gallery_categories.id = gallery.category_id', 'left')
            ->where('gallery.id', $id)
            ->where('gallery.status', 'published')
            ->first();

        if (! $item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Gallery item not found.');
        }

        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];
        $ogImage = $this->publicAssetUrl($item['image'] ?? null, $viewDefaults['defaultOgImage']);

        return view('public/gallery/show', array_merge($viewDefaults, [
            'title'           => $item['title'] . ' | ' . $siteTitle,
            'metaDescription' => $item['description'] ?: 'Gallery detail.',
            'canonicalUrl'    => site_url('gallery/' . $item['id']),
            'ogImage'         => $ogImage,
            'siteTitle'       => $siteTitle,
            'item'            => $item,
            'related'         => $this->relatedItems($item),
        ]));
    }

    private function relatedItems(array $item): array
    {
        $model = (new GalleryModel())
            ->select('gallery.*, gallery_categories.name AS category_name')
            ->join('gallery_categories', 'gallery_categories.id = gallery.category_id', 'left')
            ->where('gallery.id !=', $item['id'])
            ->where('gallery.status', 'published');

        if ($item['category_id']) {
            $model->where('gallery.category_id', $item['category_id']);
        }

        return $model->orderBy('gallery.date', 'DESC')->limit(3)->findAll();
    }

}
