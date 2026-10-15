<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Education</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/education/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Education</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Order</th><th>Institution</th><th>Program</th><th>Period</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= esc($item['sort_order']) ?></td>
                        <td><?= esc($item['institution']) ?></td>
                        <td><?= esc($item['program'] ?: $item['degree']) ?></td>
                        <td><?= esc($item['start_date'] ?: '-') ?> - <?= $item['is_current'] ? 'Current' : esc($item['end_date'] ?: '-') ?></td>
                        <td><span class="badge badge-<?= $item['status'] === 'published' ? 'success' : 'secondary' ?>"><?= esc($item['status']) ?></span></td>
                        <td class="text-right">
                            <a href="<?= site_url('admin/education/' . $item['id']) ?>" class="btn btn-xs btn-info">Detail</a>
                            <a href="<?= site_url('admin/education/' . $item['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                            <form action="<?= site_url('admin/education/' . $item['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus education ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($items === []): ?>
                    <tr><td colspan="6" class="text-center text-muted">Belum ada education.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
