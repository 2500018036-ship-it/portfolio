<?php
$imageUrl = static function (?string $path): ?string {
    return portfolio_asset_url($path);
};

$period = static function (?string $start, ?string $end): string {
    $from = $start ? date('M Y', strtotime($start)) : '';
    $to = $end ? date('M Y', strtotime($end)) : '';

    if ($from && $to) {
        return $from . ' - ' . $to;
    }

    return $from ?: $to;
};

$thumbnail = $imageUrl($project['thumbnail'] ?? null);
$dateLabel = $period($project['start_date'] ?? null, $project['end_date'] ?? null);
$sections = [
    'Description'     => $project['description'] ?? '',
    'Problem'         => $project['problem'] ?? '',
    'Solution'        => $project['solution'] ?? '',
    'Architecture'    => $project['architecture'] ?? '',
    'Features'        => $project['features'] ?? '',
    'Challenges'      => $project['challenges'] ?? '',
    'Result'          => $project['result'] ?? '',
    'Lessons Learned' => $project['lessons_learned'] ?? '',
];
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
    <?php if ($ogImage ?? null): ?><meta property="og:image" content="<?= esc($ogImage, 'attr') ?>"><?php endif; ?>
    <link rel="icon" href="<?= esc($favicon, 'attr') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #121212;
            --color-accent: #c5a059;
            --color-bg: #fcfcfc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-primary);
            overflow-x: hidden;
        }

        .serif {
            font-family: 'Playfair Display', serif;
        }

        .text-accent {
            color: var(--color-accent);
        }

        .btn-premium {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-premium::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0%;
            height: 1px;
            background: currentColor;
            transition: width 0.3s ease;
        }

        .btn-premium:hover::after {
            width: 100%;
        }
    </style>
</head>

<body class="antialiased">
    <?= $this->include('public/partials/nav') ?>

    <main class="pt-32">
        <section class="px-8 md:px-20 pb-20">
            <div class="max-w-5xl">
                <div class="flex flex-wrap gap-3 mb-4">
                    <?php if ($project['category_name']): ?>
                        <span class="text-xs font-bold uppercase tracking-widest text-accent"><?= esc($project['category_name']) ?></span>
                    <?php endif; ?>
                    <?php if ($dateLabel): ?>
                        <span class="text-xs font-bold uppercase tracking-widest text-zinc-400"><?= esc($dateLabel) ?></span>
                    <?php endif; ?>
                </div>
                <h1 class="serif text-5xl md:text-7xl mt-3 mb-6 leading-tight"><?= esc($project['title']) ?></h1>
                <?php if ($project['short_description']): ?>
                    <p class="text-xl text-zinc-500 max-w-3xl"><?= esc($project['short_description']) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-7">
                    <div class="aspect-[4/3] bg-zinc-100 overflow-hidden shadow-xl">
                        <?php if ($thumbnail): ?>
                                <img src="<?= esc($thumbnail, 'attr') ?>" alt="<?= esc($project['title'], 'attr') ?>" class="w-full h-full object-contain bg-white" loading="eager" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                            <div class="hidden w-full h-full bg-zinc-200 items-center justify-center p-8 text-center">
                                <span class="serif text-4xl"><?= esc($project['title']) ?></span>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full bg-zinc-200 flex items-center justify-center p-8 text-center">
                                <span class="serif text-4xl"><?= esc($project['title']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <aside class="lg:col-span-5">
                    <dl class="space-y-6 text-sm">
                        <?php if ($project['technologies']): ?>
                            <div>
                                <dt class="text-[10px] uppercase tracking-widest text-zinc-400 mb-2">Technologies</dt>
                                <dd><?= nl2br(esc($project['technologies'])) ?></dd>
                            </div>
                        <?php endif; ?>
                        <?php if ($dateLabel): ?>
                            <div>
                                <dt class="text-[10px] uppercase tracking-widest text-zinc-400 mb-2">Date</dt>
                                <dd><?= esc($dateLabel) ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                    <div class="flex flex-wrap gap-4 mt-10">
                        <?php if ($project['demo_url']): ?><a href="<?= esc($project['demo_url'], 'attr') ?>" target="_blank" rel="noopener" class="bg-black text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-zinc-800 transition">Live Demo</a><?php endif; ?>
                        <?php if ($project['github_url']): ?><a href="<?= esc($project['github_url'], 'attr') ?>" target="_blank" rel="noopener" class="border border-black px-8 py-4 rounded-full text-sm font-semibold hover:bg-black hover:text-white transition">GitHub</a><?php endif; ?>
                        <?php if ($project['documentation_url']): ?><a href="<?= esc($project['documentation_url'], 'attr') ?>" target="_blank" rel="noopener" class="border border-black px-8 py-4 rounded-full text-sm font-semibold hover:bg-black hover:text-white transition">Docs</a><?php endif; ?>
                    </div>
                </aside>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-8 space-y-16">
                    <?php foreach ($sections as $label => $value): ?>
                        <?php if ($value): ?>
                            <article>
                                <span class="text-xs font-bold uppercase tracking-widest text-accent"><?= esc($label) ?></span>
                                <div class="text-lg text-zinc-600 leading-relaxed mt-4"><?= nl2br(esc($value)) ?></div>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php if ($images): ?>
            <section class="px-8 md:px-20 pb-24">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Screenshots</span>
                <h2 class="serif text-4xl mt-2 mb-10">Project Images</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php foreach ($images as $image): ?>
                        <?php $src = $imageUrl($image['image'] ?? null); ?>
                        <figure class="bg-zinc-100 overflow-hidden">
                            <?php if ($src): ?>
                                <img src="<?= esc($src, 'attr') ?>" alt="<?= esc($image['caption'] ?: $project['title'], 'attr') ?>" class="w-full aspect-[4/3] object-contain bg-white" loading="lazy" decoding="async" onerror="this.classList.add('hidden')">
                            <?php endif; ?>
                            <?php if ($image['caption']): ?>
                                <figcaption class="p-4 text-sm text-zinc-500"><?= esc($image['caption']) ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>
