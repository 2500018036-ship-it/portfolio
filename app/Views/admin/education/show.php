<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header"><h3 class="card-title"><?= esc($item['institution']) ?></h3></div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Program</dt><dd class="col-sm-9"><?= esc($item['program'] ?: $item['degree'] ?: '-') ?></dd>
            <dt class="col-sm-3">Period</dt><dd class="col-sm-9"><?= esc($item['start_date'] ?: '-') ?> - <?= $item['is_current'] ? 'Current' : esc($item['end_date'] ?: '-') ?></dd>
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9"><?= nl2br(esc($item['description'] ?: '-')) ?></dd>
        </dl>
    </div>
</div>
<?= $this->endSection() ?>
