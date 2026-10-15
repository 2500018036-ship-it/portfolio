<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Profile Data</h3>
        <div class="card-tools">
            <?php if ($profile): ?>
                <a href="<?= site_url('admin/profile/' . $profile['id'] . '/edit') ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
            <?php else: ?>
                <a href="<?= site_url('admin/profile/new') ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-1"></i>Create Profile
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <?php if (! $profile): ?>
            <p class="text-muted mb-0">Profile belum dibuat.</p>
        <?php else: ?>
            <dl class="row mb-0">
                <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9"><?= esc($profile['name']) ?></dd>
                <dt class="col-sm-3">Professional Title</dt>
                <dd class="col-sm-9"><?= esc($profile['professional_title'] ?: '-') ?></dd>
                <dt class="col-sm-3">Location</dt>
                <dd class="col-sm-9"><?= esc($profile['location'] ?: '-') ?></dd>
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><?= esc($profile['email'] ?: '-') ?></dd>
                <dt class="col-sm-3">Availability</dt>
                <dd class="col-sm-9"><?= esc($profile['availability'] ?: '-') ?></dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9"><span class="badge badge-<?= $profile['status'] === 'active' ? 'success' : 'secondary' ?>"><?= esc($profile['status']) ?></span></dd>
            </dl>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
