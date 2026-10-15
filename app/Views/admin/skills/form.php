<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Skill Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?= esc(old('name', $skill['name'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Category</label>
                    <?php $selectedCategory = old('category_id', $skill['category_id'] ?? ''); ?>
                    <select name="category_id" class="form-control">
                        <option value="">No category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']) ?>" <?= (string) $selectedCategory === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Level</label>
                    <input type="text" name="level" class="form-control" value="<?= esc(old('level', $skill['level'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Icon</label>
                    <input type="text" name="icon" class="form-control" value="<?= esc(old('icon', $skill['icon'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $skill['sort_order'] ?? 0)) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control"><?= esc(old('description', $skill['description'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <?php $status = old('status', $skill['status'] ?? 'active'); ?>
                <select name="status" class="form-control">
                    <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/skills') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
