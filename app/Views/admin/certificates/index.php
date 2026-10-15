<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Certificates</h3>
                <div class="card-tools">
                    <a href="<?= site_url('admin/certificates/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Certificate</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Certificate</th>
                            <th>Issuer</th>
                            <th>Issue Date</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($certificates as $certificate): ?>
                            <tr>
                                <td><?= esc($certificate['sort_order']) ?></td>
                                <td>
                                    <?= esc($certificate['title']) ?>
                                    <?php if ($certificate['category_name']): ?><br><small class="text-muted"><?= esc($certificate['category_name']) ?></small><?php endif; ?>
                                </td>
                                <td><?= esc($certificate['issuer'] ?: '-') ?></td>
                                <td><?= esc($certificate['issue_date'] ?: '-') ?></td>
                                <td><span class="badge badge-<?= $certificate['status'] === 'published' ? 'success' : 'secondary' ?>"><?= esc($certificate['status']) ?></span></td>
                                <td class="text-right">
                                    <a href="<?= site_url('admin/certificates/' . $certificate['id']) ?>" class="btn btn-xs btn-info">Detail</a>
                                    <a href="<?= site_url('admin/certificates/' . $certificate['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/certificates/' . $certificate['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus certificate ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($certificates === []): ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada certificate.</td></tr>
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
                    <a href="<?= site_url('admin/certificates/categories/new') ?>" class="btn btn-sm btn-primary">Add</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= esc($category['name']) ?></td>
                                <td class="text-right">
                                    <a href="<?= site_url('admin/certificates/categories/' . $category['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/certificates/categories/' . $category['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus kategori certificate ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($categories === []): ?>
                            <tr><td class="text-muted">Belum ada kategori certificate.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
