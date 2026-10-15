<?php
$imageUrl = static function (?string $path): ?string {
    return portfolio_asset_url($path);
};

$dateLabel = $lab['published_at'] ? date('d M Y', strtotime($lab['published_at'])) : ($lab['created_at'] ? date('d M Y', strtotime($lab['created_at'])) : '');
$thumbnail = $imageUrl($lab['thumbnail'] ?? null);
$topologyImage = $imageUrl($lab['topology_image'] ?? null);
$sections = [
    'Objective'       => $lab['objective'] ?? '',
    'Description'     => $lab['description'] ?? '',
    'Topology Notes'  => $lab['topology'] ?? '',
    'Devices'         => $lab['devices'] ?? '',
    'Hardware'        => $lab['hardware'] ?? '',
    'Software'        => $lab['software'] ?? '',
    'IP Addressing'   => $lab['ip_addressing'] ?? '',
    'VLAN'            => $lab['vlan'] ?? '',
    'Routing'         => $lab['routing'] ?? '',
    'Firewall'        => $lab['firewall'] ?? '',
    'NAT'             => $lab['nat'] ?? '',
    'VPN'             => $lab['vpn'] ?? '',
    'Monitoring'      => $lab['monitoring'] ?? '',
    'Troubleshooting' => $lab['troubleshooting'] ?? '',
    'Testing'         => $lab['testing'] ?? '',
    'Result'          => $lab['result'] ?? '',
    'Lessons Learned' => $lab['lessons_learned'] ?? '',
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
                    <?php if ($lab['category_name']): ?>
                        <span class="text-xs font-bold uppercase tracking-widest text-accent"><?= esc($lab['category_name']) ?></span>
                    <?php endif; ?>
                    <?php if ($dateLabel): ?>
                        <span class="text-xs font-bold uppercase tracking-widest text-zinc-400"><?= esc($dateLabel) ?></span>
                    <?php endif; ?>
                </div>
                <h1 class="serif text-5xl md:text-7xl mt-3 mb-6 leading-tight"><?= esc($lab['title']) ?></h1>
                <?php if ($lab['short_description']): ?>
                    <p class="text-xl text-zinc-500 max-w-3xl"><?= esc($lab['short_description']) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-8">
                    <div class="aspect-[16/10] bg-zinc-100 overflow-hidden shadow-xl">
                        <?php if ($topologyImage): ?>
                            <img src="<?= esc($topologyImage, 'attr') ?>" alt="<?= esc($lab['title'], 'attr') ?> topology" class="w-full h-full object-contain bg-white" loading="eager" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                            <div class="hidden w-full h-full bg-zinc-200 items-center justify-center p-8 text-center">
                                <span class="serif text-4xl"><?= esc($lab['title']) ?></span>
                            </div>
                        <?php elseif ($thumbnail): ?>
                            <img src="<?= esc($thumbnail, 'attr') ?>" alt="<?= esc($lab['title'], 'attr') ?>" class="w-full h-full object-contain bg-white" loading="eager" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                            <div class="hidden w-full h-full bg-zinc-200 items-center justify-center p-8 text-center">
                                <span class="serif text-4xl"><?= esc($lab['title']) ?></span>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full bg-zinc-200 flex items-center justify-center p-8 text-center">
                                <span class="serif text-4xl"><?= esc($lab['title']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <aside class="lg:col-span-4">
                    <dl class="space-y-6 text-sm">
                        <?php if ($lab['category_name']): ?>
                            <div>
                                <dt class="text-[10px] uppercase tracking-widest text-zinc-400 mb-2">Category</dt>
                                <dd><?= esc($lab['category_name']) ?></dd>
                            </div>
                        <?php endif; ?>
                        <?php if ($dateLabel): ?>
                            <div>
                                <dt class="text-[10px] uppercase tracking-widest text-zinc-400 mb-2">Date</dt>
                                <dd><?= esc($dateLabel) ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                    <?php if ($lab['documentation_url']): ?>
                        <div class="mt-10">
                            <a href="<?= esc($lab['documentation_url'], 'attr') ?>" target="_blank" rel="noopener" class="bg-black text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-zinc-800 transition">External Docs</a>
                        </div>
                    <?php endif; ?>
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

                    <?php if ($lab['configuration']): ?>
                        <article>
                            <span class="text-xs font-bold uppercase tracking-widest text-accent">Configuration Commands</span>
                            <pre class="mt-4 bg-zinc-950 text-zinc-100 p-6 overflow-x-auto text-sm leading-relaxed"><code><?= esc($lab['configuration']) ?></code></pre>
                        </article>
                    <?php endif; ?>

                    <?php foreach (($blocks ?? []) as $block): ?>
                        <?php
                        $blockType = $block['block_type'] ?? 'text';
                        $fileUrl = $imageUrl($block['file_path'] ?? null);
                        $fileName = $block['file_original_name'] ?: basename((string) ($block['file_path'] ?? ''));
                        ?>
                        <article>
                            <span class="text-xs font-bold uppercase tracking-widest text-accent"><?= esc($block['title']) ?></span>

                            <?php if ($blockType === 'code'): ?>
                                <pre class="mt-4 bg-zinc-950 text-zinc-100 p-6 overflow-x-auto text-sm leading-relaxed"><code><?= esc($block['content'] ?? '') ?></code></pre>
                            <?php elseif ($blockType === 'image' && $fileUrl): ?>
                                <figure class="mt-4 bg-zinc-100 overflow-hidden">
                                    <img src="<?= esc($fileUrl, 'attr') ?>" alt="<?= esc($block['title'], 'attr') ?>" class="w-full max-h-[32rem] object-contain bg-white" loading="lazy" decoding="async" onerror="this.classList.add('hidden')">
                                    <?php if ($block['content']): ?>
                                        <figcaption class="p-4 text-sm text-zinc-500"><?= nl2br(esc($block['content'])) ?></figcaption>
                                    <?php endif; ?>
                                </figure>
                            <?php elseif ($blockType === 'file' && $fileUrl): ?>
                                <?php if ($block['content']): ?>
                                    <div class="text-lg text-zinc-600 leading-relaxed mt-4"><?= nl2br(esc($block['content'])) ?></div>
                                <?php endif; ?>
                                <a href="<?= esc($fileUrl, 'attr') ?>" target="_blank" rel="noopener" class="inline-flex mt-5 bg-black text-white px-6 py-3 rounded-full text-sm font-semibold hover:bg-zinc-800 transition">
                                    Open <?= esc($fileName ?: 'file') ?>
                                </a>
                            <?php else: ?>
                                <div class="text-lg text-zinc-600 leading-relaxed mt-4"><?= nl2br(esc($block['content'] ?? '')) ?></div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php if ($images): ?>
            <section class="px-8 md:px-20 pb-24">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Lab Images</span>
                <h2 class="serif text-4xl mt-2 mb-10">Documentation Gallery</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php foreach ($images as $image): ?>
                        <?php $src = $imageUrl($image['image'] ?? null); ?>
                        <figure class="bg-zinc-100 overflow-hidden">
                            <?php if ($src): ?>
                                <img src="<?= esc($src, 'attr') ?>" alt="<?= esc($image['caption'] ?: $lab['title'], 'attr') ?>" class="w-full aspect-[4/3] object-contain bg-white" loading="lazy" decoding="async" onerror="this.classList.add('hidden')">
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
