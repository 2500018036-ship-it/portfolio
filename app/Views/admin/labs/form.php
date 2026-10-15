<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Lab Form</h3></div>
        <div class="card-body">
            <div class="alert alert-warning">
                Do not store real network credentials. Use placeholders such as <code>&lt;redacted&gt;</code> in configuration examples.
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= esc(old('title', $lab['title'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= esc(old('slug', $lab['slug'] ?? '')) ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>Category</label>
                    <?php $selectedCategory = old('category_id', $lab['category_id'] ?? ''); ?>
                    <select name="category_id" class="form-control">
                        <option value="">No category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['id']) ?>" <?= (string) $selectedCategory === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Status</label>
                    <?php $status = old('status', $lab['status'] ?? 'draft'); ?>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Display Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $lab['sort_order'] ?? 0)) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Documentation URL</label>
                    <input type="url" name="documentation_url" class="form-control" value="<?= esc(old('documentation_url', $lab['documentation_url'] ?? '')) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Short Description</label>
                <textarea name="short_description" rows="3" class="form-control"><?= esc(old('short_description', $lab['short_description'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Objective</label>
                <textarea name="objective" rows="4" class="form-control"><?= esc(old('objective', $lab['objective'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" class="form-control"><?= esc(old('description', $lab['description'] ?? '')) ?></textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label>Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <div class="form-group col-md-6">
                    <label>Topology Image</label>
                    <input type="file" name="topology_image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                    <small class="form-text text-muted">Upload the original topology image. It will not be redrawn or modified.</small>
                </div>
            </div>

            <div class="form-group">
                <label>Network Topology Notes</label>
                <textarea name="topology" rows="4" class="form-control"><?= esc(old('topology', $lab['topology'] ?? '')) ?></textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>Devices</label>
                    <textarea name="devices" rows="4" class="form-control"><?= esc(old('devices', $lab['devices'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Hardware</label>
                    <textarea name="hardware" rows="4" class="form-control"><?= esc(old('hardware', $lab['hardware'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Software</label>
                    <textarea name="software" rows="4" class="form-control"><?= esc(old('software', $lab['software'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label>IP Addressing</label>
                    <textarea name="ip_addressing" rows="5" class="form-control"><?= esc(old('ip_addressing', $lab['ip_addressing'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label>VLAN</label>
                    <textarea name="vlan" rows="5" class="form-control"><?= esc(old('vlan', $lab['vlan'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>Routing</label>
                    <textarea name="routing" rows="5" class="form-control"><?= esc(old('routing', $lab['routing'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Firewall</label>
                    <textarea name="firewall" rows="5" class="form-control"><?= esc(old('firewall', $lab['firewall'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>NAT</label>
                    <textarea name="nat" rows="5" class="form-control"><?= esc(old('nat', $lab['nat'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>VPN</label>
                    <textarea name="vpn" rows="5" class="form-control"><?= esc(old('vpn', $lab['vpn'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Monitoring</label>
                    <textarea name="monitoring" rows="5" class="form-control"><?= esc(old('monitoring', $lab['monitoring'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Troubleshooting</label>
                    <textarea name="troubleshooting" rows="5" class="form-control"><?= esc(old('troubleshooting', $lab['troubleshooting'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label>Configuration Commands</label>
                <textarea name="configuration" rows="10" class="form-control" spellcheck="false"><?= esc(old('configuration', $lab['configuration'] ?? '')) ?></textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>Testing</label>
                    <textarea name="testing" rows="5" class="form-control"><?= esc(old('testing', $lab['testing'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Result</label>
                    <textarea name="result" rows="5" class="form-control"><?= esc(old('result', $lab['result'] ?? '')) ?></textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>Lessons Learned</label>
                    <textarea name="lessons_learned" rows="5" class="form-control"><?= esc(old('lessons_learned', $lab['lessons_learned'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="form-check">
                <?php $featured = old('is_featured', $lab['is_featured'] ?? 0); ?>
                <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured" <?= $featured ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_featured">Featured lab</label>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/labs') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
