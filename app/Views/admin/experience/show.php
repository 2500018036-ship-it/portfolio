<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header"><h3 class="card-title"><?= esc($item['position']) ?></h3></div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Company</dt><dd class="col-sm-9"><?= esc($item['company']) ?></dd>
            <dt class="col-sm-3">Location</dt><dd class="col-sm-9"><?= esc($item['location'] ?: '-') ?></dd>
            <dt class="col-sm-3">Period</dt><dd class="col-sm-9"><?= esc($item['start_date'] ?: '-') ?> - <?= $item['is_current'] ? 'Current' : esc($item['end_date'] ?: '-') ?></dd>
            <dt class="col-sm-3">Technologies</dt><dd class="col-sm-9"><?= nl2br(esc($item['technologies'] ?: '-')) ?></dd>
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9"><?= nl2br(esc($item['description'] ?: '-')) ?></dd>
        </dl>
    </div>
</div>
<?= $this->endSection() ?>
