<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Social Links</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/social-links/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Link</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Platform</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($links as $link): ?>
                    <tr>
                        <td><?= esc($link['sort_order']) ?></td>
                        <td><?= esc($link['platform']) ?></td>
                        <td><a href="<?= esc($link['url']) ?>" target="_blank" rel="noopener"><?= esc($link['url']) ?></a></td>
                        <td><span class="badge badge-<?= $link['status'] === 'active' ? 'success' : 'secondary' ?>"><?= esc($link['status']) ?></span></td>
                        <td class="text-right">
                            <a href="<?= site_url('admin/social-links/' . $link['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                            <form action="<?= site_url('admin/social-links/' . $link['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus social link ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($links === []): ?>
                    <tr><td colspan="5" class="text-center text-muted">Belum ada social link.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
