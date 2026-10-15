<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <?php foreach ($stats as $stat): ?>
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="small-box <?= esc($stat['class']) ?>">
                <div class="inner">
                    <h3><?= esc($stat['total']) ?></h3>
                    <p class="mb-0"><?= esc($stat['label']) ?></p>
                    <small><?= esc($stat['caption']) ?></small>
                </div>
                <div class="icon">
                    <i class="<?= esc($stat['icon']) ?>"></i>
                </div>
                <a href="<?= esc($stat['url'], 'attr') ?>" class="small-box-footer">
                    Kelola <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Portfolio Readiness</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td>Active Profile</td>
                            <td class="text-right"><span class="badge badge-<?= $readiness['activeProfile'] > 0 ? 'success' : 'secondary' ?>"><?= esc($readiness['activeProfile']) ?></span></td>
                        </tr>
                        <tr>
                            <td>Active Social Links</td>
                            <td class="text-right"><span class="badge badge-info"><?= esc($readiness['activeSocials']) ?></span></td>
                        </tr>
                        <tr>
                            <td>Featured Projects</td>
                            <td class="text-right"><span class="badge badge-info"><?= esc($readiness['featuredProjects']) ?></span></td>
                        </tr>
                        <tr>
                            <td>Featured Labs</td>
                            <td class="text-right"><span class="badge badge-info"><?= esc($readiness['featuredLabs']) ?></span></td>
                        </tr>
                        <tr>
                            <td>Site Settings</td>
                            <td class="text-right"><span class="badge badge-info"><?= esc($readiness['siteSettings']) ?></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="<?= site_url('admin/settings') ?>" class="btn btn-sm btn-primary">Open Site Settings</a>
                <a href="<?= site_url('/') ?>" target="_blank" class="btn btn-sm btn-outline-secondary" data-no-modal="1">View Public Site</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Blog Insights</h3>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <div class="h4 mb-0"><?= esc($blogInsights['total']) ?></div>
                            <small class="text-muted">Total Artikel</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <div class="h4 mb-0"><?= esc($blogInsights['published']) ?></div>
                            <small class="text-muted">Published</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <div class="h4 mb-0"><?= esc($blogInsights['draft']) ?></div>
                            <small class="text-muted">Draft</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <div class="h4 mb-0"><?= esc($blogInsights['views']) ?></div>
                            <small class="text-muted">Total Views</small>
                        </div>
                    </div>
                </div>

                <?php if ($blogInsights['topPost']): ?>
                    <div class="alert alert-light border mb-0">
                        <small class="text-muted text-uppercase">Top Article</small>
                        <div class="font-weight-bold"><?= esc($blogInsights['topPost']['title']) ?></div>
                        <small><?= esc($blogInsights['topPost']['views_count'] ?? 0) ?> views</small>
                    </div>
                <?php elseif (! $blogInsights['hasViews']): ?>
                    <p class="text-muted mb-0">Jalankan migration untuk mengaktifkan tracking views blog.</p>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada data views blog.</p>
                <?php endif; ?>
            </div>
            <?php if ($blogInsights['posts']): ?>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th class="text-right">Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($blogInsights['posts'] as $post): ?>
                                <tr>
                                    <td><a href="<?= site_url('admin/blog/' . $post['id'] . '/edit') ?>"><?= esc($post['title']) ?></a></td>
                                    <td class="text-right"><?= esc($post['views_count'] ?? 0) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Updates</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent as $item): ?>
                            <tr>
                                <td><?= esc($item['module']) ?></td>
                                <td><a href="<?= esc($item['url'], 'attr') ?>"><?= esc($item['title']) ?></a></td>
                                <td><span class="badge badge-secondary"><?= esc($item['status']) ?></span></td>
                                <td><?= esc($item['date'] ?: '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($recent === []): ?>
                            <tr><td colspan="4" class="text-center text-muted">Belum ada data portfolio.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
