<?php
$dateRange = static function (?string $issueDate, ?string $expirationDate): string {
    $issue = $issueDate ? date('d M Y', strtotime($issueDate)) : '';
    $expire = $expirationDate ? date('d M Y', strtotime($expirationDate)) : '';

    if ($issue && $expire) {
        return $issue . ' - ' . $expire;
    }

    return $issue ?: $expire;
};

$dateLabel = $dateRange($certificate['issue_date'] ?? null, $certificate['expiration_date'] ?? null);
$imageUrl = $certificate['certificate_image'] ? site_url('certificates/' . $certificate['slug'] . '/image') : null;
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
                    <?php if ($certificate['category_name']): ?>
                        <span class="text-xs font-bold uppercase tracking-widest text-accent"><?= esc($certificate['category_name']) ?></span>
                    <?php endif; ?>
                    <?php if ($dateLabel): ?>
                        <span class="text-xs font-bold uppercase tracking-widest text-zinc-400"><?= esc($dateLabel) ?></span>
                    <?php endif; ?>
                </div>
                <h1 class="serif text-5xl md:text-7xl mt-3 mb-6 leading-tight"><?= esc($certificate['title']) ?></h1>
                <?php if ($certificate['issuer']): ?>
                    <p class="text-xl text-zinc-500 max-w-3xl"><?= esc($certificate['issuer']) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section class="px-8 md:px-20 pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-7">
                    <div class="aspect-[4/3] bg-zinc-100 overflow-hidden shadow-xl">
                        <?php if ($imageUrl): ?>
                            <img src="<?= esc($imageUrl, 'attr') ?>" alt="<?= esc($certificate['title'], 'attr') ?>" class="w-full h-full object-contain bg-white" loading="eager" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                            <div class="hidden w-full h-full bg-zinc-200 items-center justify-center p-8 text-center">
                                <span class="serif text-4xl"><?= esc($certificate['title']) ?></span>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full bg-zinc-200 flex items-center justify-center p-8 text-center">
                                <span class="serif text-4xl"><?= esc($certificate['title']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <aside class="lg:col-span-5">
                    <dl class="space-y-6 text-sm">
                        <?php if ($certificate['credential_id']): ?>
                            <div>
                                <dt class="text-[10px] uppercase tracking-widest text-zinc-400 mb-2">Credential ID</dt>
                                <dd><?= esc($certificate['credential_id']) ?></dd>
                            </div>
                        <?php endif; ?>
                        <?php if ($dateLabel): ?>
                            <div>
                                <dt class="text-[10px] uppercase tracking-widest text-zinc-400 mb-2">Date</dt>
                                <dd><?= esc($dateLabel) ?></dd>
                            </div>
                        <?php endif; ?>
                        <?php if ($certificate['skills_related']): ?>
                            <div>
                                <dt class="text-[10px] uppercase tracking-widest text-zinc-400 mb-2">Skills Related</dt>
                                <dd><?= nl2br(esc($certificate['skills_related'])) ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>

                    <div class="flex flex-wrap gap-4 mt-10">
                        <?php if ($certificate['verification_url']): ?><a href="<?= esc($certificate['verification_url'], 'attr') ?>" target="_blank" rel="noopener" class="bg-black text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-zinc-800 transition">Verify</a><?php endif; ?>
                        <?php if ($certificate['certificate_file']): ?><a href="<?= site_url('certificates/' . $certificate['slug'] . '/download') ?>" class="border border-black px-8 py-4 rounded-full text-sm font-semibold hover:bg-black hover:text-white transition">Download PDF</a><?php endif; ?>
                    </div>
                </aside>
            </div>
        </section>

        <?php if ($certificate['description']): ?>
            <section class="px-8 md:px-20 pb-24">
                <div class="max-w-3xl">
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">Description</span>
                    <div class="text-lg text-zinc-600 leading-relaxed mt-4"><?= nl2br(esc($certificate['description'])) ?></div>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>
