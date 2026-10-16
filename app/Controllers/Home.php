<?php

namespace App\Controllers;

use App\Models\AchievementModel;
use App\Models\BlogPostModel;
use App\Models\CertificateModel;
use App\Models\DocumentModel;
use App\Models\DocumentationModel;
use App\Models\EducationModel;
use App\Models\ExperienceModel;
use App\Models\GalleryModel;
use App\Models\LabModel;
use App\Models\ProfileModel;
use App\Models\ProjectModel;
use App\Models\SkillModel;
use App\Models\SocialLinkModel;

class Home extends BaseController
{
    public function index(): string
    {
        $profile = (new ProfileModel())
            ->where('status', 'active')
            ->where('is_dummy', 0)
            ->orderBy('id', 'ASC')
            ->first();

        $socialLinks = (new SocialLinkModel())
            ->where('status', 'active')
            ->where('is_dummy', 0)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('platform', 'ASC')
            ->findAll();

        $skillRows = (new SkillModel())
            ->select('skills.*, skill_categories.name AS category_name')
            ->join('skill_categories', 'skill_categories.id = skills.category_id', 'left')
            ->where('skills.status', 'active')
            ->where('skills.is_dummy', 0)
            ->groupStart()
                ->where('skill_categories.status', 'active')
            ->where('skill_categories.is_dummy', 0)
                ->orWhere('skill_categories.id', null)
            ->groupEnd()
            ->orderBy('skill_categories.sort_order', 'ASC')
            ->orderBy('skills.sort_order', 'ASC')
            ->findAll();

        $skillsByCategory = [];
        foreach ($skillRows as $skill) {
            $category = $skill['category_name'] ?: 'Other';
            $skillsByCategory[$category][] = $skill;
        }

        $featuredProjects = (new ProjectModel())
            ->select('projects.*, project_categories.name AS category_name')
            ->join('project_categories', 'project_categories.id = projects.category_id', 'left')
            ->where('projects.status', 'published')
            ->where('projects.is_dummy', 0)
            ->where('projects.is_featured', 1)
            ->orderBy('projects.sort_order', 'ASC')
            ->orderBy('projects.published_at', 'DESC')
            ->limit(6)
            ->findAll();

        $experience = (new ExperienceModel())
            ->where('status', 'published')
            ->where('is_dummy', 0)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('start_date', 'DESC')
            ->limit(4)
            ->findAll();

        $education = (new EducationModel())
            ->where('status', 'published')
            ->where('is_dummy', 0)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('start_date', 'DESC')
            ->limit(4)
            ->findAll();

        $achievements = (new AchievementModel())
            ->where('status', 'published')
            ->where('is_dummy', 0)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('date', 'DESC')
            ->limit(4)
            ->findAll();

        $certificates = (new CertificateModel())
            ->select('certificates.*, certificate_categories.name AS category_name')
            ->join('certificate_categories', 'certificate_categories.id = certificates.category_id', 'left')
            ->where('certificates.status', 'published')
            ->where('certificates.is_dummy', 0)
            ->orderBy('certificates.sort_order', 'ASC')
            ->orderBy('certificates.issue_date', 'DESC')
            ->limit(6)
            ->findAll();

        $documents = (new DocumentModel())
            ->where('status', 'published')
            ->where('is_dummy', 0)
            ->where('visibility', 'public')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('uploaded_at', 'DESC')
            ->limit(4)
            ->findAll();

        $labs = (new LabModel())
            ->select('labs.*, lab_categories.name AS category_name')
            ->join('lab_categories', 'lab_categories.id = labs.category_id', 'left')
            ->where('labs.status', 'published')
            ->where('labs.is_dummy', 0)
            ->where('labs.is_featured', 1)
            ->orderBy('labs.sort_order', 'ASC')
            ->orderBy('labs.published_at', 'DESC')
            ->orderBy('labs.created_at', 'DESC')
            ->limit(4)
            ->findAll();

        $documentation = (new DocumentationModel())
            ->where('status', 'published')
            ->where('is_dummy', 0)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('published_at', 'DESC')
            ->limit(4)
            ->findAll();

        $blogPosts = (new BlogPostModel())
            ->select('blog_posts.*, blog_categories.name AS category_name')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.status', 'published')
            ->where('blog_posts.is_dummy', 0)
            ->orderBy('blog_posts.sort_order', 'ASC')
            ->orderBy('blog_posts.published_at', 'DESC')
            ->limit(4)
            ->findAll();

        $galleryItems = (new GalleryModel())
            ->select('gallery.*, gallery_categories.name AS category_name')
            ->join('gallery_categories', 'gallery_categories.id = gallery.category_id', 'left')
            ->where('gallery.status', 'published')
            ->where('gallery.is_dummy', 0)
            ->orderBy('gallery.sort_order', 'ASC')
            ->orderBy('gallery.date', 'DESC')
            ->limit(6)
            ->findAll();

        $settings = $this->publicSettings();
        $viewDefaults = $this->publicViewDefaults($settings);
        $siteTitle = trim((string) ($settings['site_title'] ?? '')) ?: ($profile['name'] ?? $viewDefaults['siteTitle']);
        $siteDescription = trim((string) ($settings['site_description'] ?? ''))
            ?: ($profile['short_bio'] ?? $profile['headline'] ?? $viewDefaults['siteDescription']);
        $metaDescription = trim((string) ($settings['seo_description'] ?? '')) ?: $siteDescription;
        $canonicalUrl = trim((string) ($settings['canonical_url'] ?? '')) ?: site_url('/');
        $contactEmail = $viewDefaults['contactEmail'] ?: ($profile['email'] ?? '');
        $contactPhone = $viewDefaults['contactPhone'] ?: ($profile['phone'] ?? '');
        $contactLocation = $viewDefaults['contactLocation'] ?: ($profile['location'] ?? '');

        return view('public/home/index', array_merge($viewDefaults, [
            'title'            => trim((string) ($settings['seo_title'] ?? '')) ?: $siteTitle,
            'siteTitle'        => $siteTitle,
            'siteDescription'  => $siteDescription,
            'metaDescription'  => $metaDescription,
            'canonicalUrl'     => $canonicalUrl,
            'seoKeywords'      => trim((string) ($settings['seo_keywords'] ?? '')),
            'contactEmail'     => $contactEmail,
            'contactPhone'     => $contactPhone,
            'contactLocation'  => $contactLocation,
            'profile'          => $profile,
            'socialLinks'      => $socialLinks,
            'skillsByCategory' => $skillsByCategory,
            'featuredProjects' => $featuredProjects,
            'experience'       => $experience,
            'education'        => $education,
            'achievements'     => $achievements,
            'certificates'     => $certificates,
            'documents'        => $documents,
            'labs'             => $labs,
            'documentation'    => $documentation,
            'blogPosts'        => $blogPosts,
            'galleryItems'     => $galleryItems,
        ]));
    }
}
