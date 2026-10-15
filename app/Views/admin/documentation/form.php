<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<?php
$publishedValue = old('published_at', $document['published_at'] ?? '');
$publishedValue = $publishedValue ? date('Y-m-d\TH:i', strtotime($publishedValue)) : '';
?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Documentation Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= esc(old('title', $document['title'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= esc(old('slug', $document['slug'] ?? '')) ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>Category</label>
                    <input type="text" name="category" class="form-control" value="<?= esc(old('category', $document['category'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control" value="<?= esc(old('author', $document['author'] ?? session('name') ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Tags</label>
                    <input type="text" name="tags" class="form-control" value="<?= esc(old('tags', $document['tags'] ?? '')) ?>" placeholder="networking, vlan, ci4">
                </div>
            </div>

            <div class="form-group">
                <label>Summary</label>
                <textarea name="summary" rows="3" class="form-control"><?= esc(old('summary', $document['summary'] ?? $document['excerpt'] ?? '')) ?></textarea>
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea name="content" rows="14" class="form-control" spellcheck="false"><?= esc(old('content', $document['content'] ?? '')) ?></textarea>
                <small class="form-text text-muted">Allowed HTML: heading, paragraph, image, code block, lists, tables, and links. Dangerous HTML is sanitized before save.</small>
            </div>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>Featured Image</label>
                    <input type="file" name="featured_image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <div class="form-group col-md-3">
                    <label>Published Date</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="<?= esc($publishedValue) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $document['sort_order'] ?? 0)) ?>">
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
            <a href="<?= site_url('admin/documentation') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
