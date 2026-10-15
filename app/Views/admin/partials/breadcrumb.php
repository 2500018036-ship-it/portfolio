<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?= esc($title ?? 'Admin') ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Admin</a></li>
                    <?php foreach (($breadcrumb ?? []) as $item): ?>
                        <li class="breadcrumb-item active"><?= esc($item) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</section>
