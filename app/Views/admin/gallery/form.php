<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Gallery Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= esc(old('title', $item['title'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Category</label>
                    <?php $selectedCategory = old('category_id', $item['category_id'] ?? ''); ?>
                    <select name="category_id" class="form-control">
                        <option value="">No category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']) ?>" <?= (string) $selectedCategory === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control"><?= esc(old('description', $item['description'] ?? '')) ?></textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp" <?= $item ? '' : 'required' ?>>
                    <small class="form-text text-muted">JPG, PNG, WebP. Max 4 MB.</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?= esc(old('date', $item['date'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $item['sort_order'] ?? 0)) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Status</label>
                    <?php $status = old('status', $item['status'] ?? 'draft'); ?>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/gallery') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
