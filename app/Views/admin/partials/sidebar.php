<?php
$disabled = 'nav-link disabled text-muted';
$path = trim(service('uri')->getPath(), '/');
$active = static fn (string $prefix): string => $path === $prefix || str_starts_with($path, $prefix . '/') ? 'active' : '';
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= site_url('admin') ?>" class="brand-link">
        <span class="brand-text font-weight-light">Portfolio Admin</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle fa-2x text-muted"></i>
            </div>
            <div class="info">
                <a href="<?= site_url('admin') ?>" class="d-block"><?= esc(session('name') ?? 'Admin') ?></a>
                <small class="text-muted"><?= esc(session('role') ?? 'admin') ?></small>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="<?= site_url('admin') ?>" class="nav-link <?= $path === 'admin' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">PORTFOLIO</li>
                <li class="nav-item"><a href="<?= site_url('admin/profile') ?>" class="nav-link <?= $active('admin/profile') ?>"><i class="nav-icon fas fa-user"></i><p>Profile</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/social-links') ?>" class="nav-link <?= $active('admin/social-links') ?>"><i class="nav-icon fas fa-share-alt"></i><p>Social Links</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/skills') ?>" class="nav-link <?= $active('admin/skills') ?>"><i class="nav-icon fas fa-layer-group"></i><p>Skills</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/projects') ?>" class="nav-link <?= $active('admin/projects') ?>"><i class="nav-icon fas fa-briefcase"></i><p>Projects</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/experience') ?>" class="nav-link <?= $active('admin/experience') ?>"><i class="nav-icon fas fa-id-card"></i><p>Experience</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/education') ?>" class="nav-link <?= $active('admin/education') ?>"><i class="nav-icon fas fa-graduation-cap"></i><p>Education</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/certificates') ?>" class="nav-link <?= $active('admin/certificates') ?>"><i class="nav-icon fas fa-certificate"></i><p>Certificates</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/achievements') ?>" class="nav-link <?= $active('admin/achievements') ?>"><i class="nav-icon fas fa-award"></i><p>Achievements</p></a></li>

                <li class="nav-header">TECHNICAL</li>
                <li class="nav-item"><a href="<?= site_url('admin/labs') ?>" class="nav-link <?= $active('admin/labs') ?>"><i class="nav-icon fas fa-network-wired"></i><p>Networking Labs</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/documentation') ?>" class="nav-link <?= $active('admin/documentation') ?>"><i class="nav-icon fas fa-book"></i><p>Documentation</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/blog') ?>" class="nav-link <?= $active('admin/blog') ?>"><i class="nav-icon fas fa-blog"></i><p>Blog</p></a></li>

                <li class="nav-header">FILES</li>
                <li class="nav-item"><a href="<?= site_url('admin/documents') ?>" class="nav-link <?= $active('admin/documents') ?>"><i class="nav-icon fas fa-file-alt"></i><p>Documents</p></a></li>
                <li class="nav-item"><a href="<?= site_url('admin/gallery') ?>" class="nav-link <?= $active('admin/gallery') ?>"><i class="nav-icon fas fa-images"></i><p>Gallery</p></a></li>

                <li class="nav-header">COMMUNICATION</li>
                <li class="nav-item"><a href="<?= site_url('admin/messages') ?>" class="nav-link <?= $active('admin/messages') ?>"><i class="nav-icon fas fa-envelope"></i><p>Messages</p></a></li>

                <li class="nav-header">SETTINGS</li>
                <li class="nav-item"><a href="<?= site_url('admin/settings') ?>" class="nav-link <?= $active('admin/settings') ?>"><i class="nav-icon fas fa-cog"></i><p>Site Settings</p></a></li>
            </ul>
        </nav>
    </div>
</aside>
