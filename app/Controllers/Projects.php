<?php

namespace App\Controllers;

use App\Models\ProjectImageModel;
use App\Models\ProjectModel;

class Projects extends BaseController
{
    public function show(string $slug): string
    {
        $project = (new ProjectModel())
            ->select('projects.*, project_categories.name AS category_name')
            ->join('project_categories', 'project_categories.id = projects.category_id', 'left')
            ->where('projects.slug', $slug)
            ->where('projects.status', 'published')
            ->first();

        if (! $project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Project not found.');
        }

        $images = (new ProjectImageModel())
            ->where('project_id', $project['id'])
            ->orderBy('is_featured', 'DESC')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = $viewDefaults['siteTitle'];
        $ogImage = $this->publicAssetUrl($project['thumbnail'] ?? null, $viewDefaults['defaultOgImage']);

        return view('public/projects/show', array_merge($viewDefaults, [
            'title'           => $project['title'] . ' | ' . $siteTitle,
            'metaDescription' => $project['short_description'] ?: 'Project detail.',
            'canonicalUrl'    => site_url('projects/' . $project['slug']),
            'ogImage'         => $ogImage,
            'project'         => $project,
            'images'          => $images,
        ]));
    }
}
