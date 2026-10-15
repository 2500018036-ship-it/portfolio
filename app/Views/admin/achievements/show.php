<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($achievement['title']) ?></h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/achievements/' . $achievement['id'] . '/edit') ?>" class="btn btn-sm btn-warning">Edit</a>
        </div>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Organization</dt><dd class="col-sm-9"><?= esc($achievement['organization'] ?: '-') ?></dd>
            <dt class="col-sm-3">Date</dt><dd class="col-sm-9"><?= esc($achievement['date'] ?: '-') ?></dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><?= esc($achievement['status']) ?></dd>
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9"><?= nl2br(esc($achievement['description'] ?: '-')) ?></dd>
        </dl>
    </div>
</div>
<?= $this->endSection() ?>
