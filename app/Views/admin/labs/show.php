<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$imageUrl = static function (?string $path): ?string {
    return portfolio_asset_url($path);
};

$sections = [
    'Short Description' => $lab['short_description'] ?? '',
    'Objective'         => $lab['objective'] ?? '',
    'Description'       => $lab['description'] ?? '',
    'Topology Notes'    => $lab['topology'] ?? '',
    'Devices'           => $lab['devices'] ?? '',
    'Hardware'          => $lab['hardware'] ?? '',
    'Software'          => $lab['software'] ?? '',
    'IP Addressing'     => $lab['ip_addressing'] ?? '',
    'VLAN'              => $lab['vlan'] ?? '',
    'Routing'           => $lab['routing'] ?? '',
    'Firewall'          => $lab['firewall'] ?? '',
    'NAT'               => $lab['nat'] ?? '',
    'VPN'               => $lab['vpn'] ?? '',
    'Monitoring'        => $lab['monitoring'] ?? '',
    'Troubleshooting'   => $lab['troubleshooting'] ?? '',
    'Testing'           => $lab['testing'] ?? '',
    'Result'            => $lab['result'] ?? '',
    'Lessons Learned'   => $lab['lessons_learned'] ?? '',
];
?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= esc($lab['title']) ?></h3>
                <div class="card-tools">
                    <a href="<?= site_url('labs/' . $lab['slug']) ?>" target="_blank" class="btn btn-sm btn-info">Public View</a>
                    <a href="<?= site_url('admin/labs/' . $lab['id'] . '/edit') ?>" class="btn btn-sm btn-warning">Edit</a>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Slug</dt><dd class="col-sm-9"><?= esc($lab['slug']) ?></dd>
                    <dt class="col-sm-3">Category</dt><dd class="col-sm-9"><?= esc($lab['category_name'] ?? '-') ?></dd>
                    <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><?= esc($lab['status']) ?><?= $lab['is_featured'] ? ' / Featured' : '' ?></dd>
                    <dt class="col-sm-3">Documentation</dt><dd class="col-sm-9"><?= $lab['documentation_url'] ? '<a href="' . esc($lab['documentation_url'], 'attr') . '" target="_blank" rel="noopener">' . esc($lab['documentation_url']) . '</a>' : '-' ?></dd>
                </dl>

                <?php if ($lab['topology_image']): ?>
                    <hr>
                    <h5>Topology Image</h5>
                    <img src="<?= esc($imageUrl($lab['topology_image']), 'attr') ?>" alt="<?= esc($lab['title'], 'attr') ?> topology" class="img-fluid rounded mb-3">
                <?php endif; ?>

                <?php foreach ($sections as $label => $value): ?>
                    <?php if ($value): ?>
                        <hr>
                        <h5><?= esc($label) ?></h5>
                        <div class="text-muted"><?= nl2br(esc($value)) ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if ($lab['configuration']): ?>
                    <hr>
                    <h5>Configuration Commands</h5>
                    <pre class="bg-dark text-light rounded p-3"><code><?= esc($lab['configuration']) ?></code></pre>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Upload Lab Images</h3></div>
            <form action="<?= site_url('admin/labs/' . $lab['id'] . '/images') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="form-group">
                        <label>Images</label>
                        <input type="file" name="lab_images[]" class="form-control-file" accept=".jpg,.jpeg,.png,.webp" multiple required>
                        <small class="form-text text-muted">Allowed: JPG, PNG, WebP. Max 4 MB per image.</small>
                    </div>
                    <div class="form-group">
                        <label>Caption</label>
                        <input type="text" name="caption" class="form-control" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label>Starting Display Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="lab_image_featured">
                        <label class="form-check-label" for="lab_image_featured">Use first uploaded image as featured image</label>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>

        <?php if ($lab['thumbnail']): ?>
            <div class="card">
                <div class="card-header"><h3 class="card-title">Current Featured Image</h3></div>
                <div class="card-body">
                    <img src="<?= esc($imageUrl($lab['thumbnail']), 'attr') ?>" alt="<?= esc($lab['title'], 'attr') ?>" class="img-fluid rounded">
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Add Custom Block</h3></div>
            <form action="<?= site_url('admin/labs/' . $lab['id'] . '/blocks') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="form-group">
                        <label>Block Title</label>
                        <input type="text" name="title" class="form-control" maxlength="160" placeholder="Example: OSPF Area Design" required>
                    </div>
                    <div class="form-group">
                        <label>Block Type</label>
                        <select name="block_type" class="form-control" required>
                            <option value="text">Text / Notes</option>
                            <option value="code">Code / Configuration</option>
                            <option value="image">Image</option>
                            <option value="file">File</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Content / Caption</label>
                        <textarea name="content" rows="5" class="form-control" placeholder="Isi catatan, command, caption gambar, atau deskripsi file."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Upload File</label>
                        <input type="file" name="block_file" class="form-control-file" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.txt">
                        <small class="form-text text-muted">Wajib untuk tipe Image/File. Allowed: JPG, PNG, WebP, PDF, DOC, DOCX, TXT.</small>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Add Block</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Custom Lab Blocks</h3></div>
    <div class="card-body">
        <?php if ($blocks ?? []): ?>
            <div class="row">
                <?php foreach ($blocks as $block): ?>
                    <?php
                    $blockType = $block['block_type'] ?? 'text';
                    $fileUrl = $imageUrl($block['file_path'] ?? null);
                    ?>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0"><?= esc($block['title']) ?></h4>
                                <div class="card-tools">
                                    <span class="badge badge-secondary"><?= esc($blockType) ?></span>
                                    <span class="badge <?= ($block['status'] ?? '') === 'active' ? 'badge-success' : 'badge-warning' ?>"><?= esc($block['status']) ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if ($blockType === 'image' && $fileUrl): ?>
                                    <img src="<?= esc($fileUrl, 'attr') ?>" alt="<?= esc($block['title'], 'attr') ?>" class="img-fluid mb-3" style="max-height:260px;object-fit:contain;background:#fff">
                                <?php elseif ($blockType === 'file' && $fileUrl): ?>
                                    <p><a href="<?= esc($fileUrl, 'attr') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info">Open <?= esc($block['file_original_name'] ?: basename((string) $block['file_path'])) ?></a></p>
                                <?php elseif ($blockType === 'code'): ?>
                                    <pre class="bg-dark text-light rounded p-3"><code><?= esc($block['content'] ?? '') ?></code></pre>
                                <?php endif; ?>

                                <?php if (($block['content'] ?? '') && $blockType !== 'code'): ?>
                                    <div class="text-muted mb-3"><?= nl2br(esc($block['content'])) ?></div>
                                <?php endif; ?>

                                <form action="<?= site_url('admin/labs/' . $lab['id'] . '/blocks/' . $block['id']) ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <label>Block Title</label>
                                        <input type="text" name="title" class="form-control form-control-sm" maxlength="160" value="<?= esc($block['title']) ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Type</label>
                                            <select name="block_type" class="form-control form-control-sm" required>
                                                <?php foreach (['text' => 'Text / Notes', 'code' => 'Code / Configuration', 'image' => 'Image', 'file' => 'File'] as $value => $label): ?>
                                                    <option value="<?= esc($value) ?>" <?= $blockType === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Order</label>
                                            <input type="number" name="sort_order" class="form-control form-control-sm" value="<?= esc($block['sort_order']) ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Status</label>
                                            <select name="status" class="form-control form-control-sm">
                                                <option value="active" <?= ($block['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                                <option value="inactive" <?= ($block['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Content / Caption</label>
                                        <textarea name="content" rows="5" class="form-control form-control-sm"><?= esc($block['content'] ?? '') ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Replace File</label>
                                        <input type="file" name="block_file" class="form-control-file" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.txt">
                                        <?php if ($block['file_path']): ?><small class="form-text text-muted">File lama akan dihapus jika diganti atau tipe diubah menjadi Text/Code.</small><?php endif; ?>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">Save Block</button>
                                </form>
                                <form action="<?= site_url('admin/labs/' . $lab['id'] . '/blocks/' . $block['id'] . '/delete') ?>" method="post" class="mt-2" onsubmit="return confirm('Hapus custom block ini? File upload terkait juga akan dihapus.')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Delete Block</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">Belum ada custom block. Tambahkan box sendiri untuk konfigurasi, diagram, file dokumentasi, atau catatan lab tambahan.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Lab Images</h3></div>
    <div class="card-body">
        <?php if ($images): ?>
            <div class="row">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <img src="<?= esc($imageUrl($image['image']), 'attr') ?>" class="card-img-top" alt="<?= esc($image['caption'] ?: $lab['title'], 'attr') ?>">
                            <div class="card-body">
                                <?php if ($image['is_featured']): ?><span class="badge badge-success mb-2">Featured</span><?php endif; ?>
                                <form action="<?= site_url('admin/labs/' . $lab['id'] . '/images/' . $image['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <label>Caption</label>
                                        <input type="text" name="caption" class="form-control form-control-sm" value="<?= esc($image['caption'] ?? '') ?>" maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label>Display Order</label>
                                        <input type="number" name="sort_order" class="form-control form-control-sm" value="<?= esc($image['sort_order']) ?>">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="lab_image_<?= esc($image['id']) ?>" <?= $image['is_featured'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="lab_image_<?= esc($image['id']) ?>">Featured image</label>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </form>
                                <form action="<?= site_url('admin/labs/' . $lab['id'] . '/images/' . $image['id'] . '/delete') ?>" method="post" class="mt-2" onsubmit="return confirm('Hapus gambar lab ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Delete Image</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">Belum ada gambar lab.</p>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
