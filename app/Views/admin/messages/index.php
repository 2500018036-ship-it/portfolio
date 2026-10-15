<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Contact Messages</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/messages') ?>" class="btn btn-sm btn-outline-secondary">All</a>
            <a href="<?= site_url('admin/messages?status=unread') ?>" class="btn btn-sm btn-outline-warning">Unread</a>
            <a href="<?= site_url('admin/messages?status=archived') ?>" class="btn btn-sm btn-outline-secondary">Archived</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr class="<?= $message['status'] === 'unread' ? 'font-weight-bold' : '' ?>">
                        <td><span class="badge badge-<?= $message['status'] === 'unread' ? 'warning' : ($message['status'] === 'archived' ? 'secondary' : 'success') ?>"><?= esc($message['status']) ?></span></td>
                        <td><?= esc($message['name']) ?><br><small class="text-muted"><?= esc($message['email']) ?></small></td>
                        <td><?= esc($message['subject'] ?: '(No subject)') ?></td>
                        <td><?= esc($message['created_at'] ?: '-') ?></td>
                        <td class="text-right">
                            <a href="<?= site_url('admin/messages/' . $message['id']) ?>" class="btn btn-xs btn-info">View</a>
                            <?php if ($message['status'] === 'unread'): ?>
                                <form action="<?= site_url('admin/messages/' . $message['id'] . '/read') ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn btn-xs btn-success">Read</button></form>
                            <?php else: ?>
                                <form action="<?= site_url('admin/messages/' . $message['id'] . '/unread') ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn btn-xs btn-warning">Unread</button></form>
                            <?php endif; ?>
                            <?php if ($message['status'] !== 'archived'): ?>
                                <form action="<?= site_url('admin/messages/' . $message['id'] . '/archive') ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn btn-xs btn-secondary">Archive</button></form>
                            <?php endif; ?>
                            <form action="<?= site_url('admin/messages/' . $message['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus message ini?')"><?= csrf_field() ?><button class="btn btn-xs btn-danger">Delete</button></form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($messages === []): ?>
                    <tr><td colspan="5" class="text-center text-muted">Belum ada message.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
