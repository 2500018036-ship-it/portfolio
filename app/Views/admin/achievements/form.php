<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Achievement Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= esc(old('title', $achievement['title'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Organization</label>
                    <input type="text" name="organization" class="form-control" value="<?= esc(old('organization', $achievement['organization'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?= esc(old('date', $achievement['date'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $achievement['sort_order'] ?? 0)) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Status</label>
                    <?php $status = old('status', $achievement['status'] ?? 'draft'); ?>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" class="form-control"><?= esc(old('description', $achievement['description'] ?? '')) ?></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <div class="form-group col-md-6">
                    <label>Document</label>
                    <input type="file" name="document" class="form-control-file" accept=".pdf,.doc,.docx">
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/achievements') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
