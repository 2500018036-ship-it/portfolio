<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Blog Posts</h3>
                <div class="card-tools">
                    <a href="<?= site_url('admin/blog/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Post</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Published</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= esc($post['sort_order'] ?? 0) ?></td>
                                <td>
                                    <?php $cover = portfolio_asset_url($post['featured_image'] ?: ($post['thumbnail'] ?? null)); ?>
                                    <?php if ($cover): ?>
                                        <img src="<?= esc($cover, 'attr') ?>" alt="<?= esc($post['title'], 'attr') ?>" style="width:72px;height:48px;object-fit:contain;background:#fff">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= esc($post['title']) ?>
                                    <br><small class="text-muted"><?= esc($post['slug']) ?></small>
                                </td>
                                <td><?= esc($post['category_name'] ?: '-') ?></td>
                                <td><?= esc($post['published_at'] ?: '-') ?></td>
                                <td><?= esc($post['views_count'] ?? 0) ?></td>
                                <td><span class="badge badge-<?= $post['status'] === 'published' ? 'success' : 'secondary' ?>"><?= esc($post['status']) ?></span></td>
                                <td class="text-right">
                                    <?php if ($post['status'] === 'published'): ?><a href="<?= site_url('blog/' . $post['slug']) ?>" target="_blank" data-no-modal="1" class="btn btn-xs btn-info">Public</a><?php endif; ?>
                                    <a href="<?= site_url('admin/blog/' . $post['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/blog/' . $post['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus blog post ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($posts === []): ?>
                            <tr><td colspan="8" class="text-center text-muted">Belum ada blog post.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Categories</h3>
                <div class="card-tools"><a href="<?= site_url('admin/blog/categories/new') ?>" class="btn btn-sm btn-primary">Add</a></div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= esc($category['name']) ?></td>
                                <td class="text-right">
                                    <a href="<?= site_url('admin/blog/categories/' . $category['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/blog/categories/' . $category['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus kategori blog ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($categories === []): ?><tr><td class="text-muted">Belum ada kategori.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tags</h3>
                <div class="card-tools"><a href="<?= site_url('admin/blog/tags/new') ?>" class="btn btn-sm btn-primary">Add</a></div>
            </div>
            <div class="card-body">
                <?php foreach ($tags as $tag): ?>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <span><?= esc($tag['name']) ?></span>
                        <span>
                            <a href="<?= site_url('admin/blog/tags/' . $tag['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                            <form action="<?= site_url('admin/blog/tags/' . $tag['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus tag blog ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                            </form>
                        </span>
                    </div>
                <?php endforeach; ?>
                <?php if ($tags === []): ?><p class="text-muted mb-0">Belum ada tag.</p><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
