<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$imageUrl = static function (?string $path): ?string {
    return portfolio_asset_url($path);
};

$details = [
    'Short Description' => $project['short_description'] ?? '',
    'Description'      => $project['description'] ?? '',
    'Problem'          => $project['problem'] ?? '',
    'Solution'         => $project['solution'] ?? '',
    'Architecture'     => $project['architecture'] ?? '',
    'Technologies'     => $project['technologies'] ?? '',
    'Features'         => $project['features'] ?? '',
    'Challenges'       => $project['challenges'] ?? '',
    'Result'           => $project['result'] ?? '',
    'Lessons Learned'  => $project['lessons_learned'] ?? '',
];
?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= esc($project['title']) ?></h3>
                <div class="card-tools">
                    <a href="<?= site_url('projects/' . $project['slug']) ?>" target="_blank" class="btn btn-sm btn-info">Public View</a>
                    <a href="<?= site_url('admin/projects/' . $project['id'] . '/edit') ?>" class="btn btn-sm btn-warning">Edit</a>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Slug</dt><dd class="col-sm-9"><?= esc($project['slug']) ?></dd>
                    <dt class="col-sm-3">Category</dt><dd class="col-sm-9"><?= esc($project['category_name'] ?? '-') ?></dd>
                    <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><?= esc($project['status']) ?><?= $project['is_featured'] ? ' / Featured' : '' ?></dd>
                    <dt class="col-sm-3">Timeline</dt><dd class="col-sm-9"><?= esc($project['start_date'] ?: '-') ?> - <?= esc($project['end_date'] ?: '-') ?></dd>
                    <dt class="col-sm-3">GitHub</dt><dd class="col-sm-9"><?= $project['github_url'] ? '<a href="' . esc($project['github_url'], 'attr') . '" target="_blank" rel="noopener">' . esc($project['github_url']) . '</a>' : '-' ?></dd>
                    <dt class="col-sm-3">Live Demo</dt><dd class="col-sm-9"><?= $project['demo_url'] ? '<a href="' . esc($project['demo_url'], 'attr') . '" target="_blank" rel="noopener">' . esc($project['demo_url']) . '</a>' : '-' ?></dd>
                    <dt class="col-sm-3">Documentation</dt><dd class="col-sm-9"><?= $project['documentation_url'] ? '<a href="' . esc($project['documentation_url'], 'attr') . '" target="_blank" rel="noopener">' . esc($project['documentation_url']) . '</a>' : '-' ?></dd>
                </dl>

                <?php foreach ($details as $label => $value): ?>
                    <?php if ($value): ?>
                        <hr>
                        <h5><?= esc($label) ?></h5>
                        <div class="text-muted"><?= nl2br(esc($value)) ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Upload Screenshots</h3></div>
            <form action="<?= site_url('admin/projects/' . $project['id'] . '/images') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="form-group">
                        <label>Images</label>
                        <input type="file" name="project_images[]" class="form-control-file" accept=".jpg,.jpeg,.png,.webp" multiple required>
                        <small class="form-text text-muted">Allowed: JPG, PNG, WebP. Max 4 MB per image.</small>
                    </div>
                    <div class="form-group">
                        <label>Caption</label>
                        <input type="text" name="caption" class="form-control" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label>Starting Display Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="project_image_featured">
                        <label class="form-check-label" for="project_image_featured">Use first uploaded image as featured image</label>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>

        <?php if ($project['thumbnail']): ?>
            <div class="card">
                <div class="card-header"><h3 class="card-title">Current Featured Image</h3></div>
                <div class="card-body">
                    <img src="<?= esc($imageUrl($project['thumbnail']), 'attr') ?>" alt="<?= esc($project['title'], 'attr') ?>" class="img-fluid rounded">
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Project Images</h3></div>
    <div class="card-body">
        <?php if ($images): ?>
            <div class="row">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <img src="<?= esc($imageUrl($image['image']), 'attr') ?>" class="card-img-top" alt="<?= esc($image['caption'] ?: $project['title'], 'attr') ?>">
                            <div class="card-body">
                                <?php if ($image['is_featured']): ?><span class="badge badge-success mb-2">Featured</span><?php endif; ?>
                                <form action="<?= site_url('admin/projects/' . $project['id'] . '/images/' . $image['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <label>Caption</label>
                                        <input type="text" name="caption" class="form-control form-control-sm" value="<?= esc($image['caption'] ?? '') ?>" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label>Display Order</label>
                                        <input type="number" name="sort_order" class="form-control form-control-sm" value="<?= esc($image['sort_order']) ?>">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="project_image_<?= esc($image['id']) ?>" <?= $image['is_featured'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="project_image_<?= esc($image['id']) ?>">Featured image</label>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </form>
                                <form action="<?= site_url('admin/projects/' . $project['id'] . '/images/' . $image['id'] . '/delete') ?>" method="post" class="mt-2" onsubmit="return confirm('Hapus gambar project ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Delete Image</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">Belum ada gambar project.</p>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
