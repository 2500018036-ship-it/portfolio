<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<?php
$publishedValue = old('published_at', $post['published_at'] ?? '');
$publishedValue = $publishedValue ? date('Y-m-d\TH:i', strtotime($publishedValue)) : '';
$selected = old('tag_ids', $selectedTagIds ?? []);
$selected = is_array($selected) ? array_map('strval', $selected) : [(string) $selected];
?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Blog Post Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= esc(old('title', $post['title'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= esc(old('slug', $post['slug'] ?? '')) ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>Category</label>
                    <?php $selectedCategory = old('category_id', $post['category_id'] ?? ''); ?>
                    <select name="category_id" class="form-control">
                        <option value="">No category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']) ?>" <?= (string) $selectedCategory === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control" value="<?= esc(old('author', $post['author'] ?? session('name') ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Tags</label>
                    <select name="tag_ids[]" class="form-control" multiple>
                        <?php foreach ($tags as $tag): ?>
                            <option value="<?= esc($tag['id']) ?>" <?= in_array((string) $tag['id'], $selected, true) ? 'selected' : '' ?>><?= esc($tag['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Excerpt</label>
                <textarea name="excerpt" rows="3" class="form-control"><?= esc(old('excerpt', $post['excerpt'] ?? '')) ?></textarea>
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea name="content" rows="14" class="form-control" spellcheck="false"><?= esc(old('content', $post['content'] ?? '')) ?></textarea>
                <small class="form-text text-muted">Allowed HTML: heading, paragraph, image, code block, lists, tables, and links. Dangerous HTML is sanitized before save.</small>
            </div>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>Featured Image</label>
                    <input type="file" name="featured_image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                    <?php $currentCover = portfolio_asset_url($post['featured_image'] ?? ($post['thumbnail'] ?? null)); ?>
                    <?php if ($currentCover): ?>
                        <div class="mt-2 border rounded p-2 bg-light">
                            <img src="<?= esc($currentCover, 'attr') ?>" alt="<?= esc($post['title'] ?? 'Current cover', 'attr') ?>" class="img-fluid" style="max-height:120px;object-fit:contain;background:#fff">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-3">
                    <label>Published Date</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="<?= esc($publishedValue) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $post['sort_order'] ?? 0)) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Status</label>
                    <?php $status = old('status', $post['status'] ?? 'draft'); ?>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
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
