<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Education Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Institution</label>
                    <input type="text" name="institution" class="form-control" value="<?= esc(old('institution', $item['institution'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Program</label>
                    <input type="text" name="program" class="form-control" value="<?= esc(old('program', $item['program'] ?? ($item['degree'] ?? ''))) ?>">
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
                <label for="is_current" class="form-check-label">Current education</label>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" class="form-control"><?= esc(old('description', $item['description'] ?? '')) ?></textarea>
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
            <a href="<?= site_url('admin/education') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
