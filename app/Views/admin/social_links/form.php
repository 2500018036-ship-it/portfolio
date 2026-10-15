<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Social Link Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Platform</label>
                    <input type="text" name="platform" class="form-control" value="<?= esc(old('platform', $link['platform'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Label</label>
                    <input type="text" name="label" class="form-control" value="<?= esc(old('label', $link['label'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Icon</label>
                    <input type="text" name="icon" class="form-control" value="<?= esc(old('icon', $link['icon'] ?? '')) ?>" placeholder="fab fa-github">
                </div>
            </div>
            <div class="form-group">
                <label>URL</label>
                <input type="url" name="url" class="form-control" value="<?= esc(old('url', $link['url'] ?? '')) ?>" required>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $link['sort_order'] ?? 0)) ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Status</label>
                    <?php $status = old('status', $link['status'] ?? 'active'); ?>
                    <select name="status" class="form-control">
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/social-links') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
