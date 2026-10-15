<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('projects/(:segment)', 'Projects::show/$1');
$routes->get('labs/(:segment)', 'Labs::show/$1');
$routes->get('certificates/(:segment)', 'Certificates::show/$1');
$routes->get('certificates/(:segment)/image', 'Certificates::image/$1');
$routes->get('certificates/(:segment)/download', 'Certificates::download/$1');
$routes->get('documents/(:num)/download', 'Documents::download/$1');
$routes->get('docs', 'Docs::index');
$routes->get('docs/(:segment)', 'Docs::show/$1');
$routes->get('blog', 'Blog::index');
$routes->get('blog/(:segment)', 'Blog::show/$1');
$routes->get('gallery', 'Gallery::index');
$routes->get('gallery/(:num)', 'Gallery::show/$1');
$routes->post('contact', 'Contact::submit');

$routes->get('admin/login', 'Admin\AuthController::login');
$routes->post('admin/login', 'Admin\AuthController::attemptLogin');
$routes->get('admin/logout', 'Admin\AuthController::logout', ['filter' => 'adminAuth']);
$routes->get('admin', 'Admin\DashboardController::index', ['filter' => 'adminAuth']);

$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes) {
    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->get('profile/new', 'Admin\ProfileController::new');
    $routes->post('profile', 'Admin\ProfileController::create');
    $routes->get('profile/(:num)/edit', 'Admin\ProfileController::edit/$1');
    $routes->post('profile/(:num)', 'Admin\ProfileController::update/$1');
    $routes->post('profile/(:num)/delete', 'Admin\ProfileController::delete/$1');

    $routes->get('social-links', 'Admin\SocialLinkController::index');
    $routes->get('social-links/new', 'Admin\SocialLinkController::new');
    $routes->post('social-links', 'Admin\SocialLinkController::create');
    $routes->get('social-links/(:num)/edit', 'Admin\SocialLinkController::edit/$1');
    $routes->post('social-links/(:num)', 'Admin\SocialLinkController::update/$1');
    $routes->post('social-links/(:num)/delete', 'Admin\SocialLinkController::delete/$1');

    $routes->get('skills', 'Admin\SkillController::index');
    $routes->get('skills/new', 'Admin\SkillController::new');
    $routes->post('skills', 'Admin\SkillController::create');
    $routes->get('skills/(:num)/edit', 'Admin\SkillController::edit/$1');
    $routes->post('skills/(:num)', 'Admin\SkillController::update/$1');
    $routes->post('skills/(:num)/delete', 'Admin\SkillController::delete/$1');
    $routes->get('skills/categories/new', 'Admin\SkillController::newCategory');
    $routes->post('skills/categories', 'Admin\SkillController::createCategory');
    $routes->get('skills/categories/(:num)/edit', 'Admin\SkillController::editCategory/$1');
    $routes->post('skills/categories/(:num)', 'Admin\SkillController::updateCategory/$1');
    $routes->post('skills/categories/(:num)/delete', 'Admin\SkillController::deleteCategory/$1');

    $routes->get('projects', 'Admin\ProjectController::index');
    $routes->get('projects/new', 'Admin\ProjectController::new');
    $routes->post('projects', 'Admin\ProjectController::create');
    $routes->get('projects/categories/new', 'Admin\ProjectController::newCategory');
    $routes->post('projects/categories', 'Admin\ProjectController::createCategory');
    $routes->get('projects/categories/(:num)/edit', 'Admin\ProjectController::editCategory/$1');
    $routes->post('projects/categories/(:num)', 'Admin\ProjectController::updateCategory/$1');
    $routes->post('projects/categories/(:num)/delete', 'Admin\ProjectController::deleteCategory/$1');
    $routes->get('projects/(:num)', 'Admin\ProjectController::show/$1');
    $routes->get('projects/(:num)/edit', 'Admin\ProjectController::edit/$1');
    $routes->post('projects/(:num)', 'Admin\ProjectController::update/$1');
    $routes->post('projects/(:num)/delete', 'Admin\ProjectController::delete/$1');
    $routes->post('projects/(:num)/images', 'Admin\ProjectController::uploadImages/$1');
    $routes->post('projects/(:num)/images/(:num)', 'Admin\ProjectController::updateImage/$1/$2');
    $routes->post('projects/(:num)/images/(:num)/delete', 'Admin\ProjectController::deleteImage/$1/$2');

    $routes->get('labs', 'Admin\LabController::index');
    $routes->get('labs/new', 'Admin\LabController::new');
    $routes->post('labs', 'Admin\LabController::create');
    $routes->get('labs/categories/new', 'Admin\LabController::newCategory');
    $routes->post('labs/categories', 'Admin\LabController::createCategory');
    $routes->get('labs/categories/(:num)/edit', 'Admin\LabController::editCategory/$1');
    $routes->post('labs/categories/(:num)', 'Admin\LabController::updateCategory/$1');
    $routes->post('labs/categories/(:num)/delete', 'Admin\LabController::deleteCategory/$1');
    $routes->get('labs/(:num)', 'Admin\LabController::show/$1');
    $routes->get('labs/(:num)/edit', 'Admin\LabController::edit/$1');
    $routes->post('labs/(:num)', 'Admin\LabController::update/$1');
    $routes->post('labs/(:num)/delete', 'Admin\LabController::delete/$1');
    $routes->post('labs/(:num)/images', 'Admin\LabController::uploadImages/$1');
    $routes->post('labs/(:num)/images/(:num)', 'Admin\LabController::updateImage/$1/$2');
    $routes->post('labs/(:num)/images/(:num)/delete', 'Admin\LabController::deleteImage/$1/$2');
    $routes->post('labs/(:num)/blocks', 'Admin\LabController::createBlock/$1');
    $routes->post('labs/(:num)/blocks/(:num)', 'Admin\LabController::updateBlock/$1/$2');
    $routes->post('labs/(:num)/blocks/(:num)/delete', 'Admin\LabController::deleteBlock/$1/$2');

    $routes->get('documentation', 'Admin\DocumentationController::index');
    $routes->get('documentation/new', 'Admin\DocumentationController::new');
    $routes->post('documentation', 'Admin\DocumentationController::create');
    $routes->get('documentation/(:num)/edit', 'Admin\DocumentationController::edit/$1');
    $routes->post('documentation/(:num)', 'Admin\DocumentationController::update/$1');
    $routes->post('documentation/(:num)/delete', 'Admin\DocumentationController::delete/$1');

    $routes->get('blog', 'Admin\BlogController::index');
    $routes->get('blog/new', 'Admin\BlogController::new');
    $routes->post('blog', 'Admin\BlogController::create');
    $routes->get('blog/categories/new', 'Admin\BlogController::newCategory');
    $routes->post('blog/categories', 'Admin\BlogController::createCategory');
    $routes->get('blog/categories/(:num)/edit', 'Admin\BlogController::editCategory/$1');
    $routes->post('blog/categories/(:num)', 'Admin\BlogController::updateCategory/$1');
    $routes->post('blog/categories/(:num)/delete', 'Admin\BlogController::deleteCategory/$1');
    $routes->get('blog/tags/new', 'Admin\BlogController::newTag');
    $routes->post('blog/tags', 'Admin\BlogController::createTag');
    $routes->get('blog/tags/(:num)/edit', 'Admin\BlogController::editTag/$1');
    $routes->post('blog/tags/(:num)', 'Admin\BlogController::updateTag/$1');
    $routes->post('blog/tags/(:num)/delete', 'Admin\BlogController::deleteTag/$1');
    $routes->get('blog/(:num)/edit', 'Admin\BlogController::edit/$1');
    $routes->post('blog/(:num)', 'Admin\BlogController::update/$1');
    $routes->post('blog/(:num)/delete', 'Admin\BlogController::delete/$1');

    $routes->get('certificates', 'Admin\CertificateController::index');
    $routes->get('certificates/new', 'Admin\CertificateController::new');
    $routes->post('certificates', 'Admin\CertificateController::create');
    $routes->get('certificates/categories/new', 'Admin\CertificateController::newCategory');
    $routes->post('certificates/categories', 'Admin\CertificateController::createCategory');
    $routes->get('certificates/categories/(:num)/edit', 'Admin\CertificateController::editCategory/$1');
    $routes->post('certificates/categories/(:num)', 'Admin\CertificateController::updateCategory/$1');
    $routes->post('certificates/categories/(:num)/delete', 'Admin\CertificateController::deleteCategory/$1');
    $routes->get('certificates/(:num)', 'Admin\CertificateController::show/$1');
    $routes->get('certificates/(:num)/edit', 'Admin\CertificateController::edit/$1');
    $routes->post('certificates/(:num)', 'Admin\CertificateController::update/$1');
    $routes->post('certificates/(:num)/delete', 'Admin\CertificateController::delete/$1');
    $routes->get('certificates/(:num)/download/(:segment)', 'Admin\CertificateController::download/$1/$2');

    $routes->get('documents', 'Admin\DocumentController::index');
    $routes->get('documents/new', 'Admin\DocumentController::new');
    $routes->post('documents', 'Admin\DocumentController::create');
    $routes->get('documents/(:num)/edit', 'Admin\DocumentController::edit/$1');
    $routes->post('documents/(:num)', 'Admin\DocumentController::update/$1');
    $routes->post('documents/(:num)/delete', 'Admin\DocumentController::delete/$1');
    $routes->get('documents/(:num)/download', 'Admin\DocumentController::download/$1');

    $routes->get('gallery', 'Admin\GalleryController::index');
    $routes->get('gallery/new', 'Admin\GalleryController::new');
    $routes->post('gallery', 'Admin\GalleryController::create');
    $routes->get('gallery/categories/new', 'Admin\GalleryController::newCategory');
    $routes->post('gallery/categories', 'Admin\GalleryController::createCategory');
    $routes->get('gallery/categories/(:num)/edit', 'Admin\GalleryController::editCategory/$1');
    $routes->post('gallery/categories/(:num)', 'Admin\GalleryController::updateCategory/$1');
    $routes->post('gallery/categories/(:num)/delete', 'Admin\GalleryController::deleteCategory/$1');
    $routes->get('gallery/(:num)/edit', 'Admin\GalleryController::edit/$1');
    $routes->post('gallery/(:num)', 'Admin\GalleryController::update/$1');
    $routes->post('gallery/(:num)/delete', 'Admin\GalleryController::delete/$1');

    $routes->get('messages', 'Admin\MessageController::index');
    $routes->get('messages/(:num)', 'Admin\MessageController::show/$1');
    $routes->post('messages/(:num)/read', 'Admin\MessageController::markRead/$1');
    $routes->post('messages/(:num)/unread', 'Admin\MessageController::markUnread/$1');
    $routes->post('messages/(:num)/archive', 'Admin\MessageController::archive/$1');
    $routes->post('messages/(:num)/delete', 'Admin\MessageController::delete/$1');

    $routes->get('settings', 'Admin\SettingsController::index');
    $routes->post('settings/account', 'Admin\SettingsController::updateAccount');
    $routes->post('settings/site', 'Admin\SettingsController::updateSite');

    $routes->get('experience', 'Admin\ExperienceController::index');
    $routes->get('experience/new', 'Admin\ExperienceController::new');
    $routes->post('experience', 'Admin\ExperienceController::create');
    $routes->get('experience/(:num)', 'Admin\ExperienceController::show/$1');
    $routes->get('experience/(:num)/edit', 'Admin\ExperienceController::edit/$1');
    $routes->post('experience/(:num)', 'Admin\ExperienceController::update/$1');
    $routes->post('experience/(:num)/delete', 'Admin\ExperienceController::delete/$1');

    $routes->get('education', 'Admin\EducationController::index');
    $routes->get('education/new', 'Admin\EducationController::new');
    $routes->post('education', 'Admin\EducationController::create');
    $routes->get('education/(:num)', 'Admin\EducationController::show/$1');
    $routes->get('education/(:num)/edit', 'Admin\EducationController::edit/$1');
    $routes->post('education/(:num)', 'Admin\EducationController::update/$1');
    $routes->post('education/(:num)/delete', 'Admin\EducationController::delete/$1');

    $routes->get('achievements', 'Admin\AchievementController::index');
    $routes->get('achievements/new', 'Admin\AchievementController::new');
    $routes->post('achievements', 'Admin\AchievementController::create');
    $routes->get('achievements/(:num)', 'Admin\AchievementController::show/$1');
    $routes->get('achievements/(:num)/edit', 'Admin\AchievementController::edit/$1');
    $routes->post('achievements/(:num)', 'Admin\AchievementController::update/$1');
    $routes->post('achievements/(:num)/delete', 'Admin\AchievementController::delete/$1');
});
