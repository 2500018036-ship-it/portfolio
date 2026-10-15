<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Blog Tag Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-5">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?= esc(old('name', $tag['name'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-5">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= esc(old('slug', $tag['slug'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label>Status</label>
                    <?php $status = old('status', $tag['status'] ?? 'active'); ?>
                    <select name="status" class="form-control">
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/blog') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
