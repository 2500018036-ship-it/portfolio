<?php
$imageUrl = static function (?string $path): ?string {
    return portfolio_asset_url($path);
};
$featured = $imageUrl($post['featured_image'] ?: ($post['thumbnail'] ?? null));
$metaImage = $ogImage ?? $featured;
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
        .article-content h1, .article-content h2, .article-content h3 { font-family: 'Playfair Display', serif; color: #111; line-height: 1.1; margin: 2rem 0 1rem; }
        .article-content h1 { font-size: 2.5rem; }
        .article-content h2 { font-size: 2rem; }
        .article-content h3 { font-size: 1.55rem; }
        .article-content p, .article-content li { color: #52525b; line-height: 1.85; }
        .article-content a { color: #111; text-decoration: underline; text-underline-offset: 4px; }
        .article-content pre { background: #111; color: #f4f4f5; padding: 1.25rem; overflow-x: auto; margin: 1.5rem 0; }
        .article-content code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.9em; }
        .article-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
        .article-content th, .article-content td { border: 1px solid #e4e4e7; padding: 0.75rem; text-align: left; }
        .article-content img { max-width: 100%; height: auto; object-fit: contain; margin: 1.5rem 0; }
    </style>
</head>

<body class="antialiased">
    <?= $this->include('public/partials/nav') ?>

    <main class="pt-32">
        <section class="px-8 md:px-20 pb-12">
            <div class="max-w-5xl">
                <div class="flex flex-wrap gap-3 mb-4">
                    <?php if ($post['category_name']): ?><span class="text-xs font-bold uppercase tracking-widest text-accent"><?= esc($post['category_name']) ?></span><?php endif; ?>
                    <?php if ($post['published_at']): ?><span class="text-xs font-bold uppercase tracking-widest text-zinc-400"><?= esc(date('d M Y', strtotime($post['published_at']))) ?></span><?php endif; ?>
                    <?php if ($post['author']): ?><span class="text-xs font-bold uppercase tracking-widest text-zinc-400"><?= esc($post['author']) ?></span><?php endif; ?>
                    <span class="text-xs font-bold uppercase tracking-widest text-zinc-400"><?= esc((string) ($post['views_count'] ?? 0)) ?> views</span>
                </div>
                <h1 class="serif text-5xl md:text-7xl mt-3 mb-6 leading-tight"><?= esc($post['title']) ?></h1>
                <?php if ($post['excerpt']): ?><p class="text-xl text-zinc-500 max-w-3xl"><?= esc($post['excerpt']) ?></p><?php endif; ?>
            </div>
        </section>

        <?php if ($featured): ?>
            <section class="px-8 md:px-20 pb-16">
                <div class="aspect-[16/8] bg-zinc-100 overflow-hidden flex items-center justify-center">
                    <img src="<?= esc($featured, 'attr') ?>" alt="<?= esc($post['title'], 'attr') ?>" class="max-h-full max-w-full object-contain bg-white" loading="eager" decoding="async" onerror="this.classList.add('hidden')">
                </div>
            </section>
        <?php endif; ?>

        <section class="px-8 md:px-20 pb-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <article class="lg:col-span-8 article-content">
                    <?= $post['content'] ?: '<p>Blog content belum tersedia.</p>' ?>
                </article>
                <aside class="lg:col-span-4">
                    <?php if ($tags): ?>
                        <div class="border p-6 mb-6">
                            <p class="text-[10px] uppercase tracking-widest text-zinc-400 mb-4">Tags</p>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($tags as $tag): ?><span class="border px-3 py-1 text-xs"><?= esc($tag['name']) ?></span><?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($relatedPosts): ?>
                        <div class="border p-6 mb-6">
                            <p class="text-[10px] uppercase tracking-widest text-zinc-400 mb-4">Related Posts</p>
                            <div class="space-y-4">
                                <?php foreach ($relatedPosts as $item): ?><a href="<?= site_url('blog/' . $item['slug']) ?>" class="block serif text-xl hover:text-accent"><?= esc($item['title']) ?></a><?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </section>

        <?php if ($relatedDocs || $relatedProjects || $relatedLabs): ?>
            <section class="px-8 md:px-20 pb-24">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Related Content</span>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <?php foreach ($relatedDocs as $item): ?><a href="<?= site_url('docs/' . $item['slug']) ?>" class="border p-6 block hover:bg-black hover:text-white transition"><p class="text-[10px] uppercase tracking-widest text-zinc-400">Doc</p><h3 class="serif text-2xl mt-3"><?= esc($item['title']) ?></h3></a><?php endforeach; ?>
                    <?php foreach ($relatedProjects as $item): ?><a href="<?= site_url('projects/' . $item['slug']) ?>" class="border p-6 block hover:bg-black hover:text-white transition"><p class="text-[10px] uppercase tracking-widest text-zinc-400">Project</p><h3 class="serif text-2xl mt-3"><?= esc($item['title']) ?></h3></a><?php endforeach; ?>
                    <?php foreach ($relatedLabs as $item): ?><a href="<?= site_url('labs/' . $item['slug']) ?>" class="border p-6 block hover:bg-black hover:text-white transition"><p class="text-[10px] uppercase tracking-widest text-zinc-400">Lab</p><h3 class="serif text-2xl mt-3"><?= esc($item['title']) ?></h3></a><?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>
