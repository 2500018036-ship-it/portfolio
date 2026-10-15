<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/partials/errors') ?>

<?php
$groupLabels = [
    'general' => 'General Identity',
    'contact' => 'Public Contact',
    'seo'     => 'SEO Metadata',
    'brand'   => 'Brand Assets',
];
$groupedSettings = [];

foreach ($settingDefinitions as $key => $definition) {
    $groupedSettings[$definition['group'] ?? 'general'][$key] = $definition;
}
?>

<div class="row">
    <div class="col-lg-5">
        <form action="<?= site_url('admin/settings/account') ?>" method="post" autocomplete="off">
            <?= csrf_field() ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Admin User</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="settings_username">Username</label>
                        <input id="settings_username" type="text" name="username" class="form-control" value="<?= esc(old('username', $user['username'] ?? '')) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="settings_email">Email</label>
                        <input id="settings_email" type="email" name="email" class="form-control" value="<?= esc(old('email', $user['email'] ?? '')) ?>" required>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="settings_current_password">Password Saat Ini</label>
                        <input id="settings_current_password" type="password" name="current_password" class="form-control">
                        <small class="form-text text-muted">Diisi hanya saat mengganti password.</small>
                    </div>
                    <div class="form-group">
                        <label for="settings_new_password">Password Baru</label>
                        <input id="settings_new_password" type="password" name="new_password" class="form-control" minlength="8">
                    </div>
                    <div class="form-group mb-0">
                        <label for="settings_password_confirm">Konfirmasi Password Baru</label>
                        <input id="settings_password_confirm" type="password" name="password_confirm" class="form-control" minlength="8">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Social Links</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Platform sosial tetap dikelola sebagai data terstruktur agar urutan, ikon, dan status aktifnya konsisten.</p>
                <a href="<?= site_url('admin/social-links') ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-share-alt mr-1"></i>Manage Social Links</a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <form action="<?= site_url('admin/settings/site') ?>" method="post">
            <?= csrf_field() ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Site Settings</h3>
                </div>
                <div class="card-body">
                    <?php foreach ($groupedSettings as $group => $definitions): ?>
                        <h5 class="text-uppercase text-muted text-sm mt-<?= $group === array_key_first($groupedSettings) ? '0' : '4' ?> mb-3"><?= esc($groupLabels[$group] ?? ucfirst($group)) ?></h5>
                        <?php foreach ($definitions as $key => $definition): ?>
                            <div class="form-group">
                                <?php $fieldId = 'setting_' . $key; ?>
                                <label for="<?= esc($fieldId, 'attr') ?>"><?= esc($definition['label']) ?></label>
                                <?php $value = old($key, $siteSettings[$key] ?? ''); ?>

                                <?php if ($definition['input'] === 'textarea'): ?>
                                    <textarea id="<?= esc($fieldId, 'attr') ?>" name="<?= esc($key, 'attr') ?>" rows="4" class="form-control"><?= esc($value) ?></textarea>
                                <?php else: ?>
                                    <input id="<?= esc($fieldId, 'attr') ?>" type="<?= esc($definition['input'], 'attr') ?>" name="<?= esc($key, 'attr') ?>" class="form-control" value="<?= esc($value) ?>">
                                <?php endif; ?>

                                <?php if (! empty($definition['help'])): ?>
                                    <small class="form-text text-muted"><?= esc($definition['help']) ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Save Site Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
