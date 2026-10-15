<?php
$imageUrl = static function (?string $path): ?string {
    return portfolio_asset_url($path);
};
$image = $imageUrl($item['image']);
$metaImage = $ogImage ?? $image;
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
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= esc($canonicalUrl, 'attr') ?>">
    <?php if ($metaImage): ?><meta property="og:image" content="<?= esc($metaImage, 'attr') ?>"><?php endif; ?>
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
            <div class="max-w-5xl">
                <div class="flex flex-wrap gap-3 mb-4">
                    <?php if ($item['category_name']): ?><span class="text-xs font-bold uppercase tracking-widest text-accent"><?= esc($item['category_name']) ?></span><?php endif; ?>
                    <?php if ($item['date']): ?><span class="text-xs font-bold uppercase tracking-widest text-zinc-400"><?= esc(date('d M Y', strtotime($item['date']))) ?></span><?php endif; ?>
                </div>
                <h1 class="serif text-5xl md:text-7xl mt-3 mb-6 leading-tight"><?= esc($item['title']) ?></h1>
                <?php if ($item['description']): ?><p class="text-xl text-zinc-500 max-w-3xl"><?= esc($item['description']) ?></p><?php endif; ?>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-20">
            <div class="bg-zinc-100">
                <img src="<?= esc($image, 'attr') ?>" alt="<?= esc($item['title'], 'attr') ?>" class="w-full max-h-[80vh] object-contain bg-white" loading="eager" decoding="async" onerror="this.classList.add('hidden')">
            </div>
        </section>

        <?php if ($related): ?>
            <section class="px-8 md:px-20 pb-24">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Related Images</span>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <?php foreach ($related as $relatedItem): ?>
                        <a href="<?= site_url('gallery/' . $relatedItem['id']) ?>" class="border bg-white block hover:bg-black hover:text-white transition overflow-hidden">
                            <div class="aspect-[4/3] bg-zinc-100"><img src="<?= esc($imageUrl($relatedItem['image']), 'attr') ?>" alt="<?= esc($relatedItem['title'], 'attr') ?>" class="w-full h-full object-contain bg-white" loading="lazy" decoding="async"></div>
                            <div class="p-5"><h3 class="serif text-2xl"><?= esc($relatedItem['title']) ?></h3></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>
