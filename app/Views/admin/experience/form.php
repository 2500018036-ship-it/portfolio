<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Experience Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Company</label>
                    <input type="text" name="company" class="form-control" value="<?= esc(old('company', $item['company'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Position</label>
                    <input type="text" name="position" class="form-control" value="<?= esc(old('position', $item['position'] ?? '')) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="<?= esc(old('location', $item['location'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Employment Type</label>
                    <input type="text" name="employment_type" class="form-control" value="<?= esc(old('employment_type', $item['employment_type'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= esc(old('start_date', $item['start_date'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= esc(old('end_date', $item['end_date'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $item['sort_order'] ?? 0)) ?>">
                </div>
            </div>
            <div class="form-check mb-3">
                <?php $current = old('is_current', $item['is_current'] ?? 0); ?>
                <input type="checkbox" name="is_current" value="1" class="form-check-input" id="is_current" <?= $current ? 'checked' : '' ?>>
                <label for="is_current" class="form-check-label">Current position</label>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" class="form-control"><?= esc(old('description', $item['description'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Technologies</label>
                <textarea name="technologies" rows="2" class="form-control"><?= esc(old('technologies', $item['technologies'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <?php $status = old('status', $item['status'] ?? 'draft'); ?>
                <select name="status" class="form-control">
                    <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/experience') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
