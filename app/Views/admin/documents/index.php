<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Documents</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/documents/new') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i>Add Document</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Version</th>
                    <th>Visibility</th>
                    <th>Status</th>
                    <th>Uploaded</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $document): ?>
                    <tr>
                        <td><?= esc($document['sort_order']) ?></td>
                        <td><?= esc($document['title']) ?></td>
                        <td><?= esc($document['category'] ?: '-') ?></td>
                        <td><?= esc($document['version'] ?: '-') ?></td>
                        <td><span class="badge badge-<?= $document['visibility'] === 'public' ? 'info' : 'secondary' ?>"><?= esc($document['visibility']) ?></span></td>
                        <td><span class="badge badge-<?= $document['status'] === 'published' ? 'success' : 'secondary' ?>"><?= esc($document['status']) ?></span></td>
                        <td><?= esc($document['uploaded_at'] ?: $document['created_at'] ?: '-') ?></td>
                        <td class="text-right">
                            <a href="<?= site_url('admin/documents/' . $document['id'] . '/download') ?>" class="btn btn-xs btn-info">Download</a>
                            <a href="<?= site_url('admin/documents/' . $document['id'] . '/edit') ?>" class="btn btn-xs btn-warning">Edit</a>
                            <form action="<?= site_url('admin/documents/' . $document['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-xs btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($documents === []): ?>
                    <tr><td colspan="8" class="text-center text-muted">Belum ada dokumen.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
