<?php
$imageUrl = static function (?string $path): ?string {
    return portfolio_asset_url($path);
};
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <meta name="description" content="<?= esc($metaDescription, 'attr') ?>">
    <link rel="canonical" href="<?= esc($canonicalUrl, 'attr') ?>">
    <meta property="og:title" content="<?= esc($title, 'attr') ?>">
    <meta property="og:description" content="<?= esc($metaDescription, 'attr') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= esc($canonicalUrl, 'attr') ?>">
    <?php if ($defaultOgImage ?? null): ?><meta property="og:image" content="<?= esc($defaultOgImage, 'attr') ?>"><?php endif; ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/articles.css') ?>?v=<?= substr(hash_file('sha256', FCPATH . 'assets/css/articles.css'), 0, 12) ?>">
    <link rel="icon" href="<?= esc($favicon, 'attr') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --color-primary: #121212; --color-accent: #c5a059; --color-bg: #fcfcfc; }
        body { font-family: 'Inter', sans-serif; background-color: var(--color-bg); color: var(--color-primary); overflow-x: hidden; }
        .serif { font-family: 'Playfair Display', serif; }
        .text-accent { color: var(--color-accent); }
        .btn-premium { position: relative; overflow: hidden; transition: all 0.3s ease; }
        .btn-premium::after { content: ''; position: absolute; bottom: 0; left: 0; width: 0%; height: 1px; background: currentColor; transition: width 0.3s ease; }
        .btn-premium:hover::after { width: 100%; }
    </style>
</head>

<body class="antialiased">
    <?= $this->include('public/partials/nav') ?>

    <main class="pt-32">
        <section class="px-8 md:px-20 pb-16">
            <div class="max-w-4xl">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Dokumentasi Implementasi</span>
                <h1 class="serif text-5xl md:text-7xl mt-3 mb-6 leading-tight">Dokumentasi Teknis</h1>
                <p class="text-xl text-zinc-500 max-w-3xl">Konfigurasi, hasil pemeriksaan, dan catatan operasi dari home lab pribadi.</p>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-12">
            <form action="<?= site_url('docs') ?>" method="get" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <label for="docs_search" class="sr-only">Cari dokumentasi</label>
                <input id="docs_search" type="search" name="q" value="<?= esc($query) ?>" placeholder="Cari dokumentasi" class="md:col-span-7 border bg-white px-5 py-4 outline-none focus:border-black">
                <label for="docs_category" class="sr-only">Documentation category</label>
                <select id="docs_category" name="category" class="md:col-span-3 border bg-white px-5 py-4 outline-none focus:border-black">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $item): ?>
                        <option value="<?= esc($item['category']) ?>" <?= $category === $item['category'] ? 'selected' : '' ?>><?= esc($item['category']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="md:col-span-2 bg-black text-white px-6 py-4 text-sm font-semibold hover:bg-zinc-800 transition">Cari</button>
            </form>
        </section>

        <section class="px-8 md:px-20 pb-24">
            <?php if ($docs): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($docs as $item): ?>
                        <?php $thumb = $imageUrl($item['featured_image'] ?: ($item['thumbnail'] ?? null)); ?>
                        <a href="<?= site_url('docs/' . $item['slug']) ?>" class="group border bg-white block hover:bg-black hover:text-white transition">
                            <div class="aspect-[4/3] bg-zinc-100 overflow-hidden">
                                <?php if ($thumb): ?>
                                    <img src="<?= esc($thumb, 'attr') ?>" alt="<?= esc($item['title'], 'attr') ?>" class="w-full h-full object-contain bg-white transition duration-700" loading="lazy" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                                    <div class="hidden w-full h-full bg-zinc-200 items-center justify-center p-8 text-center"><span class="serif text-3xl"><?= esc($item['title']) ?></span></div>
                                <?php else: ?>
                                    <div class="w-full h-full bg-zinc-200 flex items-center justify-center p-8 text-center"><span class="serif text-3xl"><?= esc($item['title']) ?></span></div>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <p class="text-[10px] uppercase tracking-widest text-zinc-400"><?= esc($item['category'] ?: 'Documentation') ?></p>
                                <h2 class="serif text-3xl mt-3 leading-tight"><?= esc($item['title']) ?></h2>
                                <?php if ($item['summary'] ?: $item['excerpt']): ?><p class="text-sm text-zinc-500 mt-4 leading-relaxed group-hover:text-zinc-300"><?= esc($item['summary'] ?: $item['excerpt']) ?></p><?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="border p-10 text-center text-zinc-500 bg-white">Belum ada documentation yang sesuai.</div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>
