<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AchievementModel;
use App\Models\BlogPostModel;
use App\Models\CertificateModel;
use App\Models\DocumentModel;
use App\Models\DocumentationModel;
use App\Models\EducationModel;
use App\Models\ExperienceModel;
use App\Models\ContactMessageModel;
use App\Models\GalleryModel;
use App\Models\LabModel;
use App\Models\ProfileModel;
use App\Models\ProjectModel;
use App\Models\SiteSettingModel;
use App\Models\SkillModel;
use App\Models\SocialLinkModel;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('admin/dashboard/index', [
            'title'        => 'Dashboard',
            'breadcrumb'   => ['Dashboard'],
            'stats'        => $this->stats(),
            'readiness'    => $this->readiness(),
            'blogInsights' => $this->blogInsights(),
            'recent'       => $this->recentItems(),
        ]);
    }

    private function stats(): array
    {
        return [
            [
                'label'     => 'Projects',
                'total'     => $this->count(ProjectModel::class),
                'caption'   => $this->count(ProjectModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-briefcase',
                'class'     => 'bg-info',
                'url'       => site_url('admin/projects'),
            ],
            [
                'label'     => 'Certificates',
                'total'     => $this->count(CertificateModel::class),
                'caption'   => $this->count(CertificateModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-certificate',
                'class'     => 'bg-success',
                'url'       => site_url('admin/certificates'),
            ],
            [
                'label'     => 'Networking Labs',
                'total'     => $this->count(LabModel::class),
                'caption'   => $this->count(LabModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-network-wired',
                'class'     => 'bg-warning',
                'url'       => site_url('admin/labs'),
            ],
            [
                'label'     => 'Documents',
                'total'     => $this->count(DocumentModel::class),
                'caption'   => $this->count(DocumentModel::class, ['status' => 'published', 'visibility' => 'public']) . ' public',
                'icon'      => 'fas fa-file-alt',
                'class'     => 'bg-secondary',
                'url'       => site_url('admin/documents'),
            ],
            [
                'label'     => 'Gallery',
                'total'     => $this->count(GalleryModel::class),
                'caption'   => $this->count(GalleryModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-images',
                'class'     => 'bg-olive',
                'url'       => site_url('admin/gallery'),
            ],
            [
                'label'     => 'Messages',
                'total'     => $this->count(ContactMessageModel::class),
                'caption'   => $this->count(ContactMessageModel::class, ['status' => 'unread']) . ' unread',
                'icon'      => 'fas fa-envelope',
                'class'     => 'bg-danger',
                'url'       => site_url('admin/messages'),
            ],
            [
                'label'     => 'Documentation',
                'total'     => $this->count(DocumentationModel::class),
                'caption'   => $this->count(DocumentationModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-book',
                'class'     => 'bg-dark',
                'url'       => site_url('admin/documentation'),
            ],
            [
                'label'     => 'Blog Posts',
                'total'     => $this->count(BlogPostModel::class),
                'caption'   => $this->count(BlogPostModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-blog',
                'class'     => 'bg-maroon',
                'url'       => site_url('admin/blog'),
            ],
            [
                'label'     => 'Skills',
                'total'     => $this->count(SkillModel::class),
                'caption'   => $this->count(SkillModel::class, ['status' => 'active']) . ' active',
                'icon'      => 'fas fa-layer-group',
                'class'     => 'bg-primary',
                'url'       => site_url('admin/skills'),
            ],
            [
                'label'     => 'Experience',
                'total'     => $this->count(ExperienceModel::class),
                'caption'   => $this->count(ExperienceModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-id-card',
                'class'     => 'bg-indigo',
                'url'       => site_url('admin/experience'),
            ],
            [
                'label'     => 'Education',
                'total'     => $this->count(EducationModel::class),
                'caption'   => $this->count(EducationModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-graduation-cap',
                'class'     => 'bg-teal',
                'url'       => site_url('admin/education'),
            ],
            [
                'label'     => 'Achievements',
                'total'     => $this->count(AchievementModel::class),
                'caption'   => $this->count(AchievementModel::class, ['status' => 'published']) . ' published',
                'icon'      => 'fas fa-award',
                'class'     => 'bg-purple',
                'url'       => site_url('admin/achievements'),
            ],
        ];
    }

    private function readiness(): array
    {
        return [
            'activeProfile'    => $this->count(ProfileModel::class, ['status' => 'active']),
            'activeSocials'    => $this->count(SocialLinkModel::class, ['status' => 'active']),
            'siteSettings'     => $this->count(SiteSettingModel::class),
            'featuredProjects' => $this->count(ProjectModel::class, ['status' => 'published', 'is_featured' => 1]),
            'featuredLabs'     => $this->count(LabModel::class, ['status' => 'published', 'is_featured' => 1]),
        ];
    }

    private function recentItems(): array
    {
        $items = [];
        $sources = [
            ['Projects', ProjectModel::class, 'title', 'admin/projects/', ''],
            ['Labs', LabModel::class, 'title', 'admin/labs/', ''],
            ['Certificates', CertificateModel::class, 'title', 'admin/certificates/', ''],
            ['Documents', DocumentModel::class, 'title', 'admin/documents/', '/edit'],
            ['Documentation', DocumentationModel::class, 'title', 'admin/documentation/', '/edit'],
            ['Blog', BlogPostModel::class, 'title', 'admin/blog/', '/edit'],
            ['Gallery', GalleryModel::class, 'title', 'admin/gallery/', '/edit'],
            ['Messages', ContactMessageModel::class, 'subject', 'admin/messages/', ''],
        ];

        foreach ($sources as [$module, $modelClass, $titleField, $urlPrefix, $urlSuffix]) {
            $rows = (new $modelClass())
                ->orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->limit(4)
                ->findAll();

            foreach ($rows as $row) {
                $items[] = [
                    'module' => $module,
                    'title'  => $row[$titleField] ?? 'Untitled',
                    'status' => $row['status'] ?? '-',
                    'date'   => $row['updated_at'] ?? $row['created_at'] ?? null,
                    'url'    => site_url($urlPrefix . $row['id'] . $urlSuffix),
                ];
            }
        }

        usort($items, static fn (array $a, array $b): int => strcmp((string) $b['date'], (string) $a['date']));

        return array_slice($items, 0, 8);
    }

    private function blogInsights(): array
    {
        $hasViews = db_connect()->fieldExists('views_count', 'blog_posts');
        $totalViews = 0;
        $topPost = null;
        $posts = [];

        if ($hasViews) {
            $summary = (new BlogPostModel())
                ->select('COALESCE(SUM(views_count), 0) AS total_views', false)
                ->first();
            $totalViews = (int) ($summary['total_views'] ?? 0);

            $topPost = (new BlogPostModel())
                ->select('id, title, slug, status, views_count, published_at')
                ->orderBy('views_count', 'DESC')
                ->orderBy('published_at', 'DESC')
                ->first();

            $posts = (new BlogPostModel())
                ->select('id, title, slug, status, views_count, published_at')
                ->orderBy('views_count', 'DESC')
                ->orderBy('published_at', 'DESC')
                ->limit(6)
                ->findAll();
        }

        return [
            'total'     => $this->count(BlogPostModel::class),
            'published' => $this->count(BlogPostModel::class, ['status' => 'published']),
            'draft'     => $this->count(BlogPostModel::class, ['status' => 'draft']),
            'views'     => $totalViews,
            'topPost'   => $topPost,
            'posts'     => $posts,
            'hasViews'  => $hasViews,
        ];
    }

    private function count(string $modelClass, array $where = []): int
    {
        $model = new $modelClass();

        foreach ($where as $field => $value) {
            $model->where($field, $value);
        }

        return (int) $model->countAllResults();
    }
}
