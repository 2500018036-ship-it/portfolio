<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>
<form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Profile Form</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?= esc(old('name', $profile['name'] ?? '')) ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Professional Title</label>
                    <input type="text" name="professional_title" class="form-control" value="<?= esc(old('professional_title', $profile['professional_title'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Headline</label>
                <input type="text" name="headline" class="form-control" value="<?= esc(old('headline', $profile['headline'] ?? '')) ?>">
            </div>
            <div class="form-group">
                <label>Short Bio</label>
                <textarea name="short_bio" rows="3" class="form-control"><?= esc(old('short_bio', $profile['short_bio'] ?? '')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Full Bio</label>
                <textarea name="full_bio" rows="6" class="form-control"><?= esc(old('full_bio', $profile['full_bio'] ?? '')) ?></textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="<?= esc(old('location', $profile['location'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc(old('email', $profile['email'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= esc(old('phone', $profile['phone'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Career Focus</label>
                    <input type="text" name="career_focus" class="form-control" value="<?= esc(old('career_focus', $profile['career_focus'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Availability</label>
                    <input type="text" name="availability" class="form-control" value="<?= esc(old('availability', $profile['availability'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>GitHub URL</label>
                    <input type="url" name="github_url" class="form-control" value="<?= esc(old('github_url', $profile['github_url'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>LinkedIn URL</label>
                    <input type="url" name="linkedin_url" class="form-control" value="<?= esc(old('linkedin_url', $profile['linkedin_url'] ?? '')) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Website URL</label>
                    <input type="url" name="website_url" class="form-control" value="<?= esc(old('website_url', $profile['website_url'] ?? '')) ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Profile Photo</label>
                    <input type="file" name="profile_image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <div class="form-group col-md-4">
                    <label>CV / Resume</label>
                    <input type="file" name="resume_file" class="form-control-file" accept=".pdf,.doc,.docx">
                </div>
                <div class="form-group col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <?php $status = old('status', $profile['status'] ?? 'active'); ?>
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= site_url('admin/profile') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
