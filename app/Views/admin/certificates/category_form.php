<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Certificate Category Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?= esc(old('name', $category['name'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= esc(old('slug', $category['slug'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" class="form-control"><?= esc(old('description', $category['description'] ?? '')) ?></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $category['sort_order'] ?? 0)) ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Status</label>
                    <?php $status = old('status', $category['status'] ?? 'active'); ?>
                    <select name="status" class="form-control">
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/certificates') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
