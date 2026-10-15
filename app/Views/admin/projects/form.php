<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Project Form</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= esc(old('title', $project['title'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= esc(old('slug', $project['slug'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Category</label>
                    <?php $selectedCategory = old('category_id', $project['category_id'] ?? ''); ?>
                    <select name="category_id" class="form-control">
                        <option value="">No category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']) ?>" <?= (string) $selectedCategory === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Status</label>
                    <?php $status = old('status', $project['status'] ?? 'draft'); ?>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $project['sort_order'] ?? 0)) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea name="short_description" rows="3" class="form-control"><?= esc(old('short_description', $project['short_description'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Full Description</label>
                <textarea name="description" rows="6" class="form-control"><?= esc(old('description', $project['description'] ?? '')) ?></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Problem</label>
                    <textarea name="problem" rows="4" class="form-control"><?= esc(old('problem', $project['problem'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label>Solution</label>
                    <textarea name="solution" rows="4" class="form-control"><?= esc(old('solution', $project['solution'] ?? '')) ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label>Architecture</label>
                <textarea name="architecture" rows="4" class="form-control"><?= esc(old('architecture', $project['architecture'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Technologies</label>
                <textarea name="technologies" rows="2" class="form-control"><?= esc(old('technologies', $project['technologies'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Features</label>
                <textarea name="features" rows="4" class="form-control"><?= esc(old('features', $project['features'] ?? '')) ?></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Challenges</label>
                    <textarea name="challenges" rows="4" class="form-control"><?= esc(old('challenges', $project['challenges'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Result</label>
                    <textarea name="result" rows="4" class="form-control"><?= esc(old('result', $project['result'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Lessons Learned</label>
                    <textarea name="lessons_learned" rows="4" class="form-control"><?= esc(old('lessons_learned', $project['lessons_learned'] ?? '')) ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Project URL</label>
                    <input type="url" name="demo_url" class="form-control" value="<?= esc(old('demo_url', $project['demo_url'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>GitHub URL</label>
                    <input type="url" name="github_url" class="form-control" value="<?= esc(old('github_url', $project['github_url'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Documentation URL</label>
                    <input type="url" name="documentation_url" class="form-control" value="<?= esc(old('documentation_url', $project['documentation_url'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= esc(old('start_date', $project['start_date'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= esc(old('end_date', $project['end_date'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Featured Image</label>
                    <input type="file" name="thumbnail" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                </div>
            </div>
            <div class="form-check">
                <?php $featured = old('is_featured', $project['is_featured'] ?? 0); ?>
                <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured" <?= $featured ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_featured">Featured project</label>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/projects') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
