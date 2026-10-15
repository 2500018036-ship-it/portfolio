<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($certificate['title']) ?></h3>
        <div class="card-tools">
            <?php if ($certificate['status'] === 'published'): ?>
                <a href="<?= site_url('certificates/' . $certificate['slug']) ?>" target="_blank" class="btn btn-sm btn-info">Public View</a>
            <?php endif; ?>
            <a href="<?= site_url('admin/certificates/' . $certificate['id'] . '/edit') ?>" class="btn btn-sm btn-warning">Edit</a>
        </div>
    </div>
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Slug</dt><dd class="col-sm-9"><?= esc($certificate['slug'] ?: '-') ?></dd>
            <dt class="col-sm-3">Category</dt><dd class="col-sm-9"><?= esc($certificate['category_name'] ?? '-') ?></dd>
            <dt class="col-sm-3">Issuer</dt><dd class="col-sm-9"><?= esc($certificate['issuer'] ?: '-') ?></dd>
            <dt class="col-sm-3">Credential ID</dt><dd class="col-sm-9"><?= esc($certificate['credential_id'] ?: '-') ?></dd>
            <dt class="col-sm-3">Issue Date</dt><dd class="col-sm-9"><?= esc($certificate['issue_date'] ?: '-') ?></dd>
            <dt class="col-sm-3">Expiration Date</dt><dd class="col-sm-9"><?= esc($certificate['expiration_date'] ?: '-') ?></dd>
            <dt class="col-sm-3">Credential URL</dt><dd class="col-sm-9"><?= $certificate['verification_url'] ? '<a href="' . esc($certificate['verification_url'], 'attr') . '" target="_blank" rel="noopener">' . esc($certificate['verification_url']) . '</a>' : '-' ?></dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><?= esc($certificate['status']) ?></dd>
            <dt class="col-sm-3">Certificate Image</dt><dd class="col-sm-9"><?= $certificate['certificate_image'] ? '<a href="' . site_url('admin/certificates/' . $certificate['id'] . '/download/image') . '">Download image</a>' : '-' ?></dd>
            <dt class="col-sm-3">Certificate PDF</dt><dd class="col-sm-9"><?= $certificate['certificate_file'] ? '<a href="' . site_url('admin/certificates/' . $certificate['id'] . '/download/file') . '">Download PDF</a>' : '-' ?></dd>
        </dl>

        <?php if ($certificate['description']): ?>
            <hr>
            <h5>Description</h5>
            <div class="text-muted"><?= nl2br(esc($certificate['description'])) ?></div>
        <?php endif; ?>

        <?php if ($certificate['skills_related']): ?>
            <hr>
            <h5>Skills Related</h5>
            <div class="text-muted"><?= nl2br(esc($certificate['skills_related'])) ?></div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
