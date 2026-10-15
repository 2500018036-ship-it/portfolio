<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Technical Documentation</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/documentation/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Documentation</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Published</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documentation as $item): ?>
                    <tr>
                        <td><?= esc($item['sort_order'] ?? 0) ?></td>
                        <td>
                            <?= esc($item['title']) ?>
                            <br><small class="text-muted"><?= esc($item['slug']) ?></small>
                        </td>
                        <td><?= esc($item['category'] ?: '-') ?></td>
                        <td><?= esc($item['author'] ?: '-') ?></td>
                        <td><?= esc($item['published_at'] ?: '-') ?></td>
                        <td><span class="badge badge-<?= $item['status'] === 'published' ? 'success' : 'secondary' ?>"><?= esc($item['status']) ?></span></td>
                        <td class="text-right">
                            <?php if ($item['status'] === 'published'): ?><a href="<?= site_url('docs/' . $item['slug']) ?>" target="_blank" data-no-modal="1" class="btn btn-xs btn-info">Public</a><?php endif; ?>
                            <a href="<?= site_url('admin/documentation/' . $item['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                            <form action="<?= site_url('admin/documentation/' . $item['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus documentation ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($documentation === []): ?>
                    <tr><td colspan="7" class="text-center text-muted">Belum ada technical documentation.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
