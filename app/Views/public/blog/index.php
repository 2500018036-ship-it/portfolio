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
        <section class="px-8 md:px-20 pb-16">
            <div class="max-w-4xl">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Journal</span>
                <h1 class="serif text-5xl md:text-7xl mt-3 mb-6 leading-tight">Blog</h1>
                <p class="text-xl text-zinc-500 max-w-3xl">Technical articles, notes, and professional updates.</p>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-12">
            <form action="<?= site_url('blog') ?>" method="get" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <label for="blog_search" class="sr-only">Search blog</label>
                <input id="blog_search" type="search" name="q" value="<?= esc($query) ?>" placeholder="Search blog" class="md:col-span-7 border bg-white px-5 py-4 outline-none focus:border-black">
                <label for="blog_category" class="sr-only">Blog category</label>
                <select id="blog_category" name="category" class="md:col-span-3 border bg-white px-5 py-4 outline-none focus:border-black">
                    <option value="">All categories</option>
                    <?php foreach ($categories as $item): ?>
                        <option value="<?= esc($item['slug']) ?>" <?= $category === $item['slug'] ? 'selected' : '' ?>><?= esc($item['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="md:col-span-2 bg-black text-white px-6 py-4 text-sm font-semibold hover:bg-zinc-800 transition">Search</button>
            </form>
        </section>

        <section class="px-8 md:px-20 pb-24">
            <?php if ($posts): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($posts as $post): ?>
                        <?php $thumb = $imageUrl($post['featured_image'] ?: ($post['thumbnail'] ?? null)); ?>
                        <a href="<?= site_url('blog/' . $post['slug']) ?>" class="group border bg-white block hover:bg-black hover:text-white transition">
                            <div class="aspect-[4/3] bg-zinc-100 overflow-hidden">
                                <?php if ($thumb): ?>
                                    <img src="<?= esc($thumb, 'attr') ?>" alt="<?= esc($post['title'], 'attr') ?>" class="w-full h-full object-contain bg-white transition duration-700" loading="lazy" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                                    <div class="hidden w-full h-full bg-zinc-200 items-center justify-center p-8 text-center"><span class="serif text-3xl"><?= esc($post['title']) ?></span></div>
                                <?php else: ?>
                                    <div class="w-full h-full bg-zinc-200 flex items-center justify-center p-8 text-center"><span class="serif text-3xl"><?= esc($post['title']) ?></span></div>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <p class="text-[10px] uppercase tracking-widest text-zinc-400"><?= esc($post['category_name'] ?: 'Blog') ?></p>
                                <?php if ($post['published_at'] || $post['author']): ?>
                                    <p class="text-[10px] uppercase tracking-widest text-zinc-400 mt-2">
                                        <?= esc($post['published_at'] ? date('d M Y', strtotime($post['published_at'])) : '') ?>
                                        <?= ($post['published_at'] && $post['author']) ? ' / ' : '' ?>
                                        <?= esc($post['author'] ?: '') ?>
                                    </p>
                                <?php endif; ?>
                                <h2 class="serif text-3xl mt-3 leading-tight"><?= esc($post['title']) ?></h2>
                                <?php if ($post['excerpt']): ?><p class="text-sm text-zinc-500 mt-4 leading-relaxed group-hover:text-zinc-300"><?= esc($post['excerpt']) ?></p><?php endif; ?>
                                <?php if (! empty($tagsByPost[(int) $post['id']])): ?>
                                    <div class="flex flex-wrap gap-2 mt-5">
                                        <?php foreach ($tagsByPost[(int) $post['id']] as $tag): ?><span class="border px-2 py-1 text-[10px] uppercase tracking-widest"><?= esc($tag['name']) ?></span><?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="border p-10 text-center text-zinc-500 bg-white">Belum ada blog post yang sesuai.</div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>
