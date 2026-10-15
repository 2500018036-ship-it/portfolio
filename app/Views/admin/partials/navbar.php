<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= site_url('admin') ?>" class="nav-link">Dashboard</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <span class="nav-link text-muted">
                <i class="far fa-user mr-1"></i><?= esc(session('name') ?? 'Admin') ?>
            </span>
        </li>
        <li class="nav-item">
            <a href="<?= site_url('admin/logout') ?>" class="nav-link">
                <i class="fas fa-sign-out-alt mr-1"></i>Logout
            </a>
        </li>
    </ul>
</nav>
