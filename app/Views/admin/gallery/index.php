<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gallery</h3>
                <div class="card-tools">
                    <a href="<?= site_url('admin/gallery/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Image</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?php if ($item['image']): ?>
                                        <img src="<?= esc(portfolio_asset_url($item['image']), 'attr') ?>" alt="<?= esc($item['title'], 'attr') ?>" style="width:64px;height:48px;object-fit:contain;background:#fff">
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($item['title']) ?></td>
                                <td><?= esc($item['category_name'] ?: '-') ?></td>
                                <td><?= esc($item['date'] ?: '-') ?></td>
                                <td><?= esc($item['sort_order'] ?? 0) ?></td>
                                <td><span class="badge badge-<?= $item['status'] === 'published' ? 'success' : 'secondary' ?>"><?= esc($item['status']) ?></span></td>
                                <td class="text-right">
                                    <?php if ($item['status'] === 'published'): ?><a href="<?= site_url('gallery/' . $item['id']) ?>" target="_blank" data-no-modal="1" class="btn btn-xs btn-info">Public</a><?php endif; ?>
                                    <a href="<?= site_url('admin/gallery/' . $item['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/gallery/' . $item['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus gallery item ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($items === []): ?>
                            <tr><td colspan="7" class="text-center text-muted">Belum ada gallery image.</td></tr>
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
                <div class="card-tools">
                    <a href="<?= site_url('admin/gallery/categories/new') ?>" class="btn btn-sm btn-primary">Add</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>
                                    <?= esc($category['name']) ?>
                                    <br><small class="text-muted"><?= esc($category['status']) ?></small>
                                </td>
                                <td class="text-right">
                                    <a href="<?= site_url('admin/gallery/categories/' . $category['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/gallery/categories/' . $category['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus kategori gallery ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($categories === []): ?>
                            <tr><td class="text-muted">Belum ada kategori gallery.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
