<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Document Form</h3></div>
        <div class="card-body">
            <div class="alert alert-info">
                Files are stored in <code>writable/uploads</code> and downloaded through controller routes.
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= esc(old('title', $document['title'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Category</label>
                    <?php $selectedCategory = old('category', $document['category'] ?? 'CV'); ?>
                    <select name="category" class="form-control">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category) ?>" <?= $selectedCategory === $category ? 'selected' : '' ?>><?= esc($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Version</label>
                    <input type="text" name="version" class="form-control" value="<?= esc(old('version', $document['version'] ?? '')) ?>" placeholder="v1">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" class="form-control"><?= esc(old('description', $document['description'] ?? '')) ?></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>File</label>
                    <input type="file" name="file" class="form-control-file" accept=".pdf,.doc,.docx" <?= $document ? '' : 'required' ?>>
                    <small class="form-text text-muted">PDF, DOC, DOCX. Max 10 MB.</small>
                </div>
                <div class="form-group col-md-2">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $document['sort_order'] ?? 0)) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Visibility</label>
                    <?php $visibility = old('visibility', $document['visibility'] ?? 'private'); ?>
                    <select name="visibility" class="form-control">
                        <option value="private" <?= $visibility === 'private' ? 'selected' : '' ?>>Private</option>
                        <option value="public" <?= $visibility === 'public' ? 'selected' : '' ?>>Public</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Status</label>
                    <?php $status = old('status', $document['status'] ?? 'draft'); ?>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/documents') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
