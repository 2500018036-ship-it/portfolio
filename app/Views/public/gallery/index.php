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
        <section class="px-8 md:px-20 pb-12">
            <div class="max-w-4xl">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Visual Records</span>
                <h1 class="serif text-5xl md:text-7xl mt-3 mb-6 leading-tight">Gallery</h1>
                <p class="text-xl text-zinc-500 max-w-3xl">Documentation images from projects, labs, campus activities, certifications, and events.</p>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-10">
            <div class="flex flex-wrap gap-3">
                <a href="<?= site_url('gallery') ?>" class="border px-4 py-2 text-xs uppercase tracking-widest <?= $category === '' ? 'bg-black text-white' : 'bg-white' ?>">All</a>
                <?php foreach ($categories as $item): ?>
                    <a href="<?= site_url('gallery?category=' . urlencode($item['slug'])) ?>" class="border px-4 py-2 text-xs uppercase tracking-widest <?= $category === $item['slug'] ? 'bg-black text-white' : 'bg-white' ?>"><?= esc($item['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-24">
            <?php if ($items): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($items as $idx => $item): ?>
                        <?php $image = $imageUrl($item['image']); ?>
                        <a href="<?= site_url('gallery/' . $item['id']) ?>" class="group block bg-white border overflow-hidden <?= $idx % 3 === 1 ? 'lg:mt-10' : '' ?>">
                            <div class="aspect-[4/5] bg-zinc-100 overflow-hidden">
                                <img src="<?= esc($image, 'attr') ?>" alt="<?= esc($item['title'], 'attr') ?>" class="w-full h-full object-contain bg-white transition duration-700" loading="lazy" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                                <div class="hidden w-full h-full bg-zinc-200 items-center justify-center p-8 text-center"><span class="serif text-3xl"><?= esc($item['title']) ?></span></div>
                            </div>
                            <div class="p-6">
                                <p class="text-[10px] uppercase tracking-widest text-zinc-400"><?= esc($item['category_name'] ?: 'Gallery') ?></p>
                                <h2 class="serif text-3xl mt-3"><?= esc($item['title']) ?></h2>
                                <?php if ($item['description']): ?><p class="text-sm text-zinc-500 mt-3 leading-relaxed"><?= esc($item['description']) ?></p><?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="border p-10 text-center text-zinc-500 bg-white">Belum ada gallery image.</div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>
