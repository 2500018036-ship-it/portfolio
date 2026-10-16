<?php

namespace App\Controllers;

use App\Models\LabImageModel;
use App\Models\LabBlockModel;
use App\Models\LabModel;

class Labs extends BaseController
{
    public function show(string $slug): string
    {
        $lab = (new LabModel())
            ->select('labs.*, lab_categories.name AS category_name')
            ->join('lab_categories', 'lab_categories.id = labs.category_id', 'left')
            ->where('labs.slug', $slug)
            ->where('labs.status', 'published')
            ->where('labs.is_dummy', 0)
            ->first();

        if (! $lab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lab not found.');
        }

        $images = (new LabImageModel())
            ->where('lab_id', $lab['id'])
            ->orderBy('is_featured', 'DESC')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $blocks = (new LabBlockModel())
            ->where('lab_id', $lab['id'])
            ->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];
        $ogImage = $this->publicAssetUrl($lab['topology_image'] ?: ($lab['thumbnail'] ?? null), $viewDefaults['defaultOgImage']);

        return view('public/labs/show', array_merge($viewDefaults, [
            'title'           => $lab['title'] . ' | ' . $siteTitle,
            'metaDescription' => $lab['short_description'] ?: ($lab['objective'] ?: 'Networking lab documentation.'),
            'canonicalUrl'    => site_url('labs/' . $lab['slug']),
            'ogImage'         => $ogImage,
            'lab'             => $lab,
            'images'          => $images,
            'blocks'          => $blocks,
        ]));
    }
}
