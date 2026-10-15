<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Achievements</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/achievements/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Achievement</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Order</th><th>Title</th><th>Organization</th><th>Date</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
            <tbody>
                <?php foreach ($achievements as $achievement): ?>
                    <tr>
                        <td><?= esc($achievement['sort_order']) ?></td>
                        <td><?= esc($achievement['title']) ?></td>
                        <td><?= esc($achievement['organization'] ?: '-') ?></td>
                        <td><?= esc($achievement['date'] ?: '-') ?></td>
                        <td><span class="badge badge-<?= $achievement['status'] === 'published' ? 'success' : 'secondary' ?>"><?= esc($achievement['status']) ?></span></td>
                        <td class="text-right">
                            <a href="<?= site_url('admin/achievements/' . $achievement['id']) ?>" class="btn btn-xs btn-info">Detail</a>
                            <a href="<?= site_url('admin/achievements/' . $achievement['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                            <form action="<?= site_url('admin/achievements/' . $achievement['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus achievement ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($achievements === []): ?>
                    <tr><td colspan="6" class="text-center text-muted">Belum ada achievement.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
