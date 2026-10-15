<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($message['subject'] ?: '(No subject)') ?></h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/messages') ?>" class="btn btn-sm btn-secondary">Back</a>
        </div>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Name</dt><dd class="col-sm-9"><?= esc($message['name']) ?></dd>
            <dt class="col-sm-3">Email</dt><dd class="col-sm-9"><a href="mailto:<?= esc($message['email'], 'attr') ?>"><?= esc($message['email']) ?></a></dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><?= esc($message['status']) ?></dd>
            <dt class="col-sm-3">Received</dt><dd class="col-sm-9"><?= esc($message['created_at'] ?: '-') ?></dd>
            <dt class="col-sm-3">IP</dt><dd class="col-sm-9"><?= esc($message['ip_address'] ?: '-') ?></dd>
            <dt class="col-sm-3">User Agent</dt><dd class="col-sm-9"><?= esc($message['user_agent'] ?: '-') ?></dd>
        </dl>
        <hr>
        <div class="lead" style="white-space: pre-wrap"><?= esc($message['message']) ?></div>
    </div>
    <div class="card-footer">
        <form action="<?= site_url('admin/messages/' . $message['id'] . '/unread') ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn btn-warning">Mark Unread</button></form>
        <form action="<?= site_url('admin/messages/' . $message['id'] . '/archive') ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn btn-secondary">Archive</button></form>
        <form action="<?= site_url('admin/messages/' . $message['id'] . '/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus message ini?')"><?= csrf_field() ?><button class="btn btn-danger">Delete</button></form>
    </div>
</div>
<?= $this->endSection() ?>
