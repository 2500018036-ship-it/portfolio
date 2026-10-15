<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Networking Labs</h3>
                <div class="card-tools">
                    <a href="<?= site_url('admin/labs/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Lab</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($labs as $lab): ?>
                            <tr>
                                <td><?= esc($lab['sort_order']) ?></td>
                                <td><?= esc($lab['title']) ?></td>
                                <td><?= esc($lab['category_name'] ?? '-') ?></td>
                                <td><span class="badge badge-<?= $lab['status'] === 'published' ? 'success' : 'secondary' ?>"><?= esc($lab['status']) ?></span></td>
                                <td><?= $lab['is_featured'] ? 'Yes' : 'No' ?></td>
                                <td class="text-right">
                                    <a href="<?= site_url('admin/labs/' . $lab['id']) ?>" class="btn btn-xs btn-info">Detail</a>
                                    <a href="<?= site_url('admin/labs/' . $lab['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/labs/' . $lab['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus lab ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($labs === []): ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada lab.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lab Categories</h3>
                <div class="card-tools">
                    <a href="<?= site_url('admin/labs/categories/new') ?>" class="btn btn-sm btn-primary">Add</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= esc($category['name']) ?></td>
                                <td class="text-right">
                                    <a href="<?= site_url('admin/labs/categories/' . $category['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/labs/categories/' . $category['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus kategori lab ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($categories === []): ?>
                            <tr><td class="text-muted">Belum ada kategori lab.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
