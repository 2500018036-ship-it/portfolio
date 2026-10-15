<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Certificate Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Certificate Name</label>
                    <input type="text" name="title" class="form-control" value="<?= esc(old('title', $certificate['title'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= esc(old('slug', $certificate['slug'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Category</label>
                    <?php $selectedCategory = old('category_id', $certificate['category_id'] ?? ''); ?>
                    <select name="category_id" class="form-control">
                        <option value="">No category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']) ?>" <?= (string) $selectedCategory === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Issuer</label>
                    <input type="text" name="issuer" class="form-control" value="<?= esc(old('issuer', $certificate['issuer'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Credential ID</label>
                    <input type="text" name="credential_id" class="form-control" value="<?= esc(old('credential_id', $certificate['credential_id'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Issue Date</label>
                    <input type="date" name="issue_date" class="form-control" value="<?= esc(old('issue_date', $certificate['issue_date'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Expiration Date</label>
                    <input type="date" name="expiration_date" class="form-control" value="<?= esc(old('expiration_date', $certificate['expiration_date'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Credential URL</label>
                    <input type="url" name="verification_url" class="form-control" value="<?= esc(old('verification_url', $certificate['verification_url'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control"><?= esc(old('description', $certificate['description'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Skills Related</label>
                <textarea name="skills_related" rows="3" class="form-control"><?= esc(old('skills_related', $certificate['skills_related'] ?? '')) ?></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Certificate Image</label>
                    <input type="file" name="certificate_image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                    <small class="form-text text-muted">Stored in writable/uploads. JPG, PNG, WebP. Max 4 MB.</small>
                </div>
                <div class="form-group col-md-6">
                    <label>Certificate PDF</label>
                    <input type="file" name="certificate_file" class="form-control-file" accept=".pdf">
                    <small class="form-text text-muted">Stored in writable/uploads. PDF only. Max 8 MB.</small>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $certificate['sort_order'] ?? 0)) ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Status</label>
                    <?php $status = old('status', $certificate['status'] ?? 'draft'); ?>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
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
