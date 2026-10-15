<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Skills</h3>
                <div class="card-tools">
                    <a href="<?= site_url('admin/skills/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Skill</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td><?= esc($skill['sort_order']) ?></td>
                                <td><?= esc($skill['name']) ?></td>
                                <td><?= esc($skill['category_name'] ?? '-') ?></td>
                                <td><?= esc($skill['level'] ?: '-') ?></td>
                                <td><span class="badge badge-<?= $skill['status'] === 'active' ? 'success' : 'secondary' ?>"><?= esc($skill['status']) ?></span></td>
                                <td class="text-right">
                                    <a href="<?= site_url('admin/skills/' . $skill['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/skills/' . $skill['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus skill ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($skills === []): ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada skill.</td></tr>
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
                    <a href="<?= site_url('admin/skills/categories/new') ?>" class="btn btn-sm btn-primary">Add</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= esc($category['name']) ?></td>
                                <td class="text-right">
                                    <a href="<?= site_url('admin/skills/categories/' . $category['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/skills/categories/' . $category['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($categories === []): ?>
                            <tr><td class="text-muted">Belum ada kategori.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
