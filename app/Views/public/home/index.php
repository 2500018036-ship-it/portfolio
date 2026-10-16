<?php
$imageUrl = static function (?string $path): ?string {
    return portfolio_asset_url($path);
};

$period = static function (?string $start, ?string $end, bool $current = false, bool $yearOnly = false): string {
    $format = $yearOnly ? 'Y' : 'M Y';
    $from = $start ? date($format, strtotime($start)) : '';
    $to = $current ? 'Sekarang' : ($end ? date($format, strtotime($end)) : '');

    if ($from && $to) {
        return $from . ' - ' . $to;
    }

    return $from ?: ($to ?: '');
};

$heroTitle = $profile['name'] ?? $siteTitle;
$heroSubtitle = $profile['professional_title'] ?? $profile['headline'] ?? 'Jaringan & Infrastruktur IT';
$heroIntro = $profile['short_bio'] ?? $profile['career_focus'] ?? 'Catatan proyek, home lab, dan pengalaman teknis Muhammad Azril Achdi.';
$hasAbout = $profile && (($profile['full_bio'] ?? '') || ($profile['short_bio'] ?? '') || ($profile['career_focus'] ?? '') || ($profile['location'] ?? ''));
$hasContact = true;
$navLinks = [
    ['Beranda', site_url('/')],
    ['Profil', '#about'],
    ['Keahlian', '#skills'],
    ['Proyek', '#projects'],
    ['Home Lab', '#labs'],
    ['Sertifikat', '#certificates'],
    ['Pengalaman', '#experience'],
    ['Pendidikan', '#education'],
    ['Dokumentasi', site_url('docs')],
    ['Blog', site_url('blog')],
    ['Galeri', site_url('gallery')],
    ['Kontak', '#contact'],
];
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= base_url('assets/css/articles.css') ?>?v=<?= substr(hash_file('sha256', FCPATH . 'assets/css/articles.css'), 0, 12) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <meta name="description" content="<?= esc($metaDescription, 'attr') ?>">
    <link rel="canonical" href="<?= esc($canonicalUrl, 'attr') ?>">
    <meta property="og:title" content="<?= esc($title, 'attr') ?>">
    <meta property="og:description" content="<?= esc($metaDescription, 'attr') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= esc($canonicalUrl, 'attr') ?>">
    <?php if ($seoKeywords ?? ''): ?><meta name="keywords" content="<?= esc($seoKeywords, 'attr') ?>"><?php endif; ?>
    <?php if ($defaultOgImage ?? null): ?><meta property="og:image" content="<?= esc($defaultOgImage, 'attr') ?>"><?php endif; ?>
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

        .border-accent {
            border-color: var(--color-accent);
        }

        .bg-accent {
            background-color: var(--color-accent);
        }

        .hover\:text-accent:hover {
            color: var(--color-accent);
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .grid-asymmetric .portfolio-card:nth-child(even) {
            margin-top: 4rem;
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
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

        .mobile-panel {
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-panel.open {
            transform: translateX(0);
        }
    </style>
</head>

<body class="antialiased">
    <div id="loader" class="fixed inset-0 z-[100] bg-white flex items-center justify-center transition-opacity duration-700">
        <div class="text-center">
            <h1 class="serif max-w-[90vw] whitespace-normal break-words px-4 text-2xl leading-tight sm:text-3xl tracking-widest animate-pulse"><?= esc($siteTitle) ?></h1>
            <div class="mt-4 h-px w-24 bg-black mx-auto origin-left scale-x-0 transition-transform duration-1000" id="loader-bar"></div>
        </div>
    </div>

    <nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-500 py-6 px-6 md:px-12 lg:px-20 flex justify-between items-center gap-6 bg-transparent">
        <a href="<?= site_url('/') ?>" class="serif text-2xl tracking-tighter font-bold shrink-0 max-w-[58vw] truncate" aria-label="<?= esc($siteTitle, 'attr') ?>">
            <?php if ($siteLogo ?? null): ?>
                <img src="<?= esc($siteLogo, 'attr') ?>" alt="<?= esc($siteTitle, 'attr') ?>" class="h-8 w-auto max-w-[180px] object-contain" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                <span class="hidden"><?= esc($siteTitle) ?></span>
            <?php else: ?>
                <?= esc($siteTitle) ?>
            <?php endif; ?>
        </a>

        <div class="hidden xl:flex flex-wrap justify-end gap-x-5 gap-y-2 text-[10px] tracking-widest uppercase font-semibold max-w-[70vw]">
            <?php foreach ($navLinks as [$label, $url]): ?>
                <a href="<?= esc($url, 'attr') ?>" class="btn-premium"><?= esc($label) ?></a>
            <?php endforeach; ?>
        </div>

        <button id="mobile-menu-toggle" class="xl:hidden hover:opacity-50 transition" onclick="toggleMenu()" aria-label="Open navigation" aria-controls="mobile-menu" aria-expanded="false">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path data-menu-icon="open" d="M4 8h16M4 16h10" />
                <path data-menu-icon="close" class="hidden" d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
    </nav>

    <aside id="mobile-menu" class="mobile-panel fixed top-0 right-0 h-full w-full max-w-[320px] bg-white z-[90] shadow-2xl p-8 xl:hidden overflow-y-auto">
        <div class="flex items-center justify-between mb-12">
            <span class="serif text-2xl tracking-tighter font-bold truncate pr-6"><?= esc($siteTitle) ?></span>
            <button onclick="toggleMenu(false)" class="hover:opacity-50 transition" aria-label="Close navigation">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
        <div class="space-y-6 text-sm uppercase tracking-widest font-semibold">
            <?php foreach ($navLinks as [$label, $url]): ?>
                <a href="<?= esc($url, 'attr') ?>" onclick="toggleMenu(false)" class="block btn-premium"><?= esc($label) ?></a>
            <?php endforeach; ?>
        </div>
    </aside>

    <section class="relative min-h-screen w-full flex flex-col justify-center overflow-hidden bg-[#e5e5e5] px-6 pt-32 pb-16 md:h-screen md:px-20 md:py-0">
        <div class="relative z-10 order-2 max-w-2xl md:order-none md:max-w-[48vw] lg:max-w-[50rem]">
            <p class="text-xs uppercase tracking-[0.22em] text-accent font-semibold mb-6"><?= esc($heroTitle) ?></p>
            <h1 class="serif text-5xl sm:text-7xl md:text-8xl xl:text-9xl mb-6 leading-none reveal break-words">
                <?= esc($heroSubtitle) ?>
            </h1>
            <p class="text-gray-600 mb-8 max-w-md reveal" style="transition-delay: 0.2s"><?= esc($heroIntro) ?></p>
            <div class="flex flex-wrap gap-4 reveal" style="transition-delay: 0.4s">
                <?php if ($featuredProjects): ?>
                    <a href="#projects" class="bg-black text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-zinc-800 transition shadow-xl">Lihat Proyek</a>
                <?php endif; ?>
                <a href="#contact" class="border border-black px-8 py-4 rounded-full text-sm font-semibold hover:bg-black hover:text-white transition">Contact</a>
            </div>
        </div>

    
    </section>

    <?php if ($hasAbout): ?>
        <section id="about" class="py-24 px-8 md:px-20 bg-white">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <div class="lg:col-span-4 reveal">
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">About</span>
                    <h2 class="serif text-5xl mt-2 leading-tight"><?= esc($profile['professional_title'] ?: 'Profile') ?></h2>
                </div>
                <div class="lg:col-span-8 reveal" style="transition-delay: 0.2s">
                    <p class="text-2xl md:text-3xl serif leading-snug mb-8"><?= esc($profile['headline'] ?: $profile['short_bio']) ?></p>
                    <?php if ($profile['full_bio'] ?? null): ?>
                        <div class="text-gray-600 leading-relaxed space-y-4 max-w-3xl"><?= nl2br(esc($profile['full_bio'])) ?></div>
                    <?php elseif ($profile['short_bio'] ?? null): ?>
                        <div class="text-gray-600 leading-relaxed space-y-4 max-w-3xl"><?= nl2br(esc($profile['short_bio'])) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($skillsByCategory): ?>
        <section id="skills" class="py-24 px-8 md:px-20">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">Bidang Keahlian</span>
                    <h2 class="serif text-4xl mt-2">Skills</h2>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php $delay = 0; ?>
                <?php foreach ($skillsByCategory as $category => $skills): ?>
                    <div class="group relative min-h-[22rem] overflow-hidden bg-zinc-100 cursor-default reveal" style="transition-delay: <?= esc((string) $delay) ?>s">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/[0.03] transition"></div>
                        <div class="relative h-full flex flex-col justify-between p-8">
                            <div>
                                <p class="serif text-2xl mb-2"><?= esc($category) ?></p>
                                <p class="text-[10px] uppercase tracking-widest text-zinc-400"><?= count($skills) ?> skills</p>
                            </div>
                            <div class="space-y-3">
                                <?php foreach ($skills as $skill): ?>
                                    <div>
                                        <div class="flex justify-between gap-3 text-sm font-semibold">
                                            <span><?= esc($skill['name']) ?></span>
                                            <?php if ($skill['level']): ?><span class="text-zinc-400 font-normal"><?= esc($skill['level']) ?></span><?php endif; ?>
                                        </div>
                                        <?php if ($skill['description']): ?><p class="text-xs text-zinc-500 mt-1"><?= esc($skill['description']) ?></p><?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php $delay += 0.1; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($featuredProjects): ?>
        <section id="projects" class="py-24 px-8 md:px-20 bg-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-16 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">Karya Pilihan</span>
                    <h2 class="serif text-5xl mt-2">Proyek Pilihan</h2>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-16 grid-asymmetric">
                <?php foreach ($featuredProjects as $idx => $project): ?>
                    <?php $thumb = $imageUrl($project['thumbnail'] ?? null); ?>
                    <a href="<?= site_url('projects/' . $project['slug']) ?>" class="portfolio-card group relative reveal block" style="transition-delay: <?= esc((string) ($idx * 0.1)) ?>s">
                        <div class="aspect-[3/4] overflow-hidden bg-zinc-100 relative mb-6">
                            <?php if ($thumb): ?>
                                <img src="<?= esc($thumb, 'attr') ?>" alt="<?= esc($project['title'], 'attr') ?>" class="w-full h-full object-contain bg-white transition duration-1000" loading="lazy" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                                <div class="hidden absolute inset-0 bg-zinc-200 items-center justify-center p-8 text-center">
                                    <span class="serif text-3xl"><?= esc($project['title']) ?></span>
                                </div>
                            <?php else: ?>
                                <div class="absolute inset-0 bg-zinc-200 flex items-center justify-center p-8 text-center">
                                    <span class="serif text-3xl"><?= esc($project['title']) ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition flex items-center justify-center opacity-0 group-hover:opacity-100 duration-500">
                                <span class="bg-white text-black px-6 py-3 text-[10px] font-bold uppercase tracking-widest shadow-xl transform translate-y-4 group-hover:translate-y-0 transition duration-500">Lihat Detail</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="serif text-xl mb-2"><?= esc($project['title']) ?></h3>
                            <?php if ($project['category_name']): ?><p class="text-[10px] uppercase tracking-widest text-zinc-400 mb-3"><?= esc($project['category_name']) ?></p><?php endif; ?>
                            <?php if ($project['short_description']): ?><p class="text-sm text-zinc-500 leading-relaxed"><?= esc($project['short_description']) ?></p><?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($experience || $education): ?>
        <section id="experience" class="py-24 px-8 md:px-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <?php if ($experience): ?>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-accent">Experience</span>
                        <h2 class="serif text-4xl mt-2 mb-10">Pengalaman Praktis</h2>
                        <div class="space-y-8">
                            <?php foreach ($experience as $item): ?>
                                <div class="border-t pt-6 reveal">
                                    <p class="text-[10px] uppercase tracking-widest text-zinc-400"><?= esc($period($item['start_date'], $item['end_date'], (bool) $item['is_current'])) ?></p>
                                    <h3 class="serif text-2xl mt-2"><?= esc($item['position']) ?></h3>
                                    <p class="font-semibold"><?= esc($item['company']) ?><?= $item['location'] ? ' / ' . esc($item['location']) : '' ?></p>
                                    <?php if ($item['description']): ?><p class="text-sm text-zinc-500 mt-3 leading-relaxed"><?= esc($item['description']) ?></p><?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($education): ?>
                    <div id="education">
                        <span class="text-xs font-bold uppercase tracking-widest text-accent">Education</span>
                        <h2 class="serif text-4xl mt-2 mb-10">Pendidikan</h2>
                        <div class="space-y-8">
                            <?php foreach ($education as $item): ?>
                                <div class="border-t pt-6 reveal">
                                    <p class="text-[10px] uppercase tracking-widest text-zinc-400"><?= esc($period($item['start_date'], $item['end_date'], (bool) $item['is_current'], true)) ?></p>
                                    <h3 class="serif text-2xl mt-2"><?= esc($item['institution']) ?></h3>
                                    <?php if ($item['program'] || $item['degree']): ?><p class="font-semibold"><?= esc($item['program'] ?: $item['degree']) ?></p><?php endif; ?>
                                    <?php if ($item['description']): ?><p class="text-sm text-zinc-500 mt-3 leading-relaxed"><?= esc($item['description']) ?></p><?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?= $this->include('public/home/lab_section') ?>

    <?php if ($certificates || $documentation || $achievements): ?>
        <section id="certificates" class="py-24 px-8 md:px-20 bg-white">
            <div class="mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Sertifikat & Dokumentasi</span>
                <h2 class="serif text-5xl mt-2">Rekam Pembelajaran</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($certificates as $item): ?>
                    <?php $certificateImage = $item['certificate_image'] ? site_url('certificates/' . $item['slug'] . '/image') : null; ?>
                    <a href="<?= site_url('certificates/' . $item['slug']) ?>" class="group border reveal block overflow-hidden hover:bg-black hover:text-white transition">
                        <?php if ($certificateImage): ?>
                            <div class="bg-zinc-100 p-4 flex items-center justify-center">
                                <img src="<?= esc($certificateImage, 'attr') ?>" alt="<?= esc($item['title'], 'attr') ?>" class="max-h-48 max-w-full object-contain bg-white" loading="lazy" decoding="async" onerror="this.closest('div').classList.add('hidden')">
                            </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <p class="text-[10px] uppercase tracking-widest text-zinc-400">Certificate</p>
                            <h3 class="serif text-2xl mt-3"><?= esc($item['title']) ?></h3>
                            <p class="text-sm text-zinc-500 mt-2 group-hover:text-zinc-300"><?= esc($item['issuer'] ?: $item['category_name'] ?: '') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
                <?php if ($documentation): ?><span id="documentation" class="sr-only" aria-hidden="true">Documentation</span><?php endif; ?>
                <?php foreach ($documentation as $item): ?>
                    <?php $docImage = $imageUrl($item['featured_image'] ?: ($item['thumbnail'] ?? null)); ?>
                    <a href="<?= site_url('docs/' . $item['slug']) ?>" class="group border reveal block overflow-hidden hover:bg-black hover:text-white transition">
                        <?php if ($docImage): ?>
                            <div class="bg-zinc-100 p-4 flex items-center justify-center">
                                <img src="<?= esc($docImage, 'attr') ?>" alt="<?= esc($item['title'], 'attr') ?>" class="max-h-48 max-w-full object-contain bg-white" loading="lazy" decoding="async" onerror="this.closest('div').classList.add('hidden')">
                            </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <p class="text-[10px] uppercase tracking-widest text-zinc-400">Documentation</p>
                            <h3 class="serif text-2xl mt-3"><?= esc($item['title']) ?></h3>
                            <p class="text-sm text-zinc-500 mt-2 group-hover:text-zinc-300"><?= esc(($item['summary'] ?? '') ?: $item['excerpt'] ?: $item['category'] ?: '') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
                <?php foreach ($achievements as $item): ?>
                    <?php $achievementImage = $imageUrl($item['image'] ?? null); ?>
                    <div class="border reveal overflow-hidden">
                        <?php if ($achievementImage): ?>
                            <div class="bg-zinc-100 p-4 flex items-center justify-center">
                                <img src="<?= esc($achievementImage, 'attr') ?>" alt="<?= esc($item['title'], 'attr') ?>" class="max-h-48 max-w-full object-contain bg-white" loading="lazy" decoding="async" onerror="this.closest('div').classList.add('hidden')">
                            </div>
                        <?php endif; ?>
                        <div class="p-6">
                            <p class="text-[10px] uppercase tracking-widest text-zinc-400">Achievement</p>
                            <h3 class="serif text-2xl mt-3"><?= esc($item['title']) ?></h3>
                            <p class="text-sm text-zinc-500 mt-2"><?= esc($item['organization'] ?: '') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?= $this->include('public/home/blog_section') ?>

    <?php if ($documents): ?>
        <section id="documents" class="py-24 px-8 md:px-20">
            <div class="mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-accent">Unduhan</span>
                <h2 class="serif text-5xl mt-2">Dokumen</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($documents as $document): ?>
                    <div class="border p-6 reveal">
                        <p class="text-[10px] uppercase tracking-widest text-zinc-400"><?= esc($document['category'] ?: 'Document') ?></p>
                        <h3 class="serif text-2xl mt-3"><?= esc($document['title']) ?></h3>
                        <?php if ($document['version']): ?><p class="text-xs uppercase tracking-widest text-zinc-400 mt-2"><?= esc($document['version']) ?></p><?php endif; ?>
                        <?php if ($document['description']): ?><p class="text-sm text-zinc-500 mt-4 leading-relaxed"><?= esc($document['description']) ?></p><?php endif; ?>
                        <a href="<?= site_url('documents/' . $document['id'] . '/download') ?>" class="inline-block mt-6 text-xs font-bold uppercase tracking-widest btn-premium">Unduh</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($galleryItems): ?>
        <section id="gallery" class="py-24 px-8 md:px-20 bg-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">Dokumentasi Visual</span>
                    <h2 class="serif text-5xl mt-2">Gallery</h2>
                </div>
                <a href="<?= site_url('gallery') ?>" class="text-xs font-bold uppercase tracking-widest btn-premium">Lihat Semua</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($galleryItems as $idx => $item): ?>
                    <?php $galleryImage = $imageUrl($item['image'] ?? null); ?>
                    <a href="<?= site_url('gallery/' . $item['id']) ?>" class="group block reveal <?= $idx % 3 === 1 ? 'md:mt-10' : '' ?>">
                        <div class="aspect-[4/5] bg-zinc-100 overflow-hidden">
                            <img src="<?= esc($galleryImage, 'attr') ?>" alt="<?= esc($item['title'], 'attr') ?>" class="w-full h-full object-contain bg-white transition duration-700" loading="lazy" decoding="async" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
                            <div class="hidden w-full h-full bg-zinc-200 items-center justify-center p-8 text-center"><span class="serif text-3xl"><?= esc($item['title']) ?></span></div>
                        </div>
                        <div class="mt-5">
                            <p class="text-[10px] uppercase tracking-widest text-zinc-400"><?= esc($item['category_name'] ?: 'Gallery') ?></p>
                            <h3 class="serif text-2xl mt-2"><?= esc($item['title']) ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($hasContact): ?>
        <section id="contact" class="py-24 px-8 md:px-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">
                <div class="reveal">
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">Contact</span>
                    <h2 class="serif text-5xl mt-2 mb-6">Mari Terhubung</h2>
                    <?php if ($profile['availability'] ?? null): ?><p class="text-gray-500 mb-8"><?= esc($profile['availability']) ?></p><?php endif; ?>
                    <div class="space-y-4 text-sm">
                        <?php if ($contactEmail): ?><p><span class="text-zinc-400 uppercase tracking-widest text-[10px] block">Email</span><a href="mailto:<?= esc($contactEmail, 'attr') ?>" class="btn-premium"><?= esc($contactEmail) ?></a></p><?php endif; ?>
                        <?php if ($contactPhone): ?><p><span class="text-zinc-400 uppercase tracking-widest text-[10px] block">Phone</span><?= esc($contactPhone) ?></p><?php endif; ?>
                        <?php if ($contactLocation): ?><p><span class="text-zinc-400 uppercase tracking-widest text-[10px] block">Location</span><?= esc($contactLocation) ?></p><?php endif; ?>
                    </div>

                    <?php if ($socialLinks): ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10">
                            <?php foreach ($socialLinks as $link): ?>
                                <a href="<?= esc($link['url'], 'attr') ?>" target="_blank" rel="noopener" class="border p-6 hover:bg-black hover:text-white transition">
                                    <p class="text-[10px] uppercase tracking-widest opacity-60"><?= esc($link['platform']) ?></p>
                                    <p class="serif text-2xl mt-2"><?= esc($link['label'] ?: $link['platform']) ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="reveal" style="transition-delay: 0.2s">
                    <?php if (session()->getFlashdata('contact_success')): ?>
                        <div class="border border-green-300 bg-green-50 text-green-700 p-4 mb-6"><?= esc(session()->getFlashdata('contact_success')) ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('contact_error')): ?>
                        <div class="border border-red-300 bg-red-50 text-red-700 p-4 mb-6"><?= esc(session()->getFlashdata('contact_error')) ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('contact_errors')): ?>
                        <div class="border border-red-300 bg-red-50 text-red-700 p-4 mb-6">
                            <ul class="list-disc pl-5">
                                <?php foreach (session()->getFlashdata('contact_errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('contact') ?>" method="post" class="space-y-4">
                        <?= csrf_field() ?>
                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label for="contact_name" class="sr-only">Name</label>
                            <input id="contact_name" type="text" name="name" value="<?= esc(old('name')) ?>" placeholder="Nama" class="border bg-white px-5 py-4 outline-none focus:border-black" required>
                            <label for="contact_email" class="sr-only">Email</label>
                            <input id="contact_email" type="email" name="email" value="<?= esc(old('email')) ?>" placeholder="Email" class="border bg-white px-5 py-4 outline-none focus:border-black" required>
                        </div>
                        <label for="contact_subject" class="sr-only">Subject</label>
                        <input id="contact_subject" type="text" name="subject" value="<?= esc(old('subject')) ?>" placeholder="Subjek" class="w-full border bg-white px-5 py-4 outline-none focus:border-black">
                        <label for="contact_message" class="sr-only">Message</label>
                        <textarea id="contact_message" name="message" rows="7" placeholder="Pesan" class="w-full border bg-white px-5 py-4 outline-none focus:border-black" required><?= esc(old('message')) ?></textarea>
                        <button type="submit" class="bg-black text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-zinc-800 transition">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <footer class="bg-white pt-24 pb-12 px-8 md:px-20 border-t">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
            <div class="col-span-1 md:col-span-2">
                <h1 class="serif text-3xl mb-6"><?= esc($siteTitle) ?></h1>
                <p class="text-gray-400 max-w-sm mb-8 leading-relaxed"><?= esc($siteDescription) ?></p>
                <?php if ($socialLinks): ?>
                    <div class="flex flex-wrap gap-6">
                        <?php foreach ($socialLinks as $link): ?>
                            <a href="<?= esc($link['url'], 'attr') ?>" target="_blank" rel="noopener" class="text-xs font-bold tracking-widest uppercase btn-premium"><?= esc($link['label'] ?: $link['platform']) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-widest mb-6">Navigation</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    <?php foreach ($navLinks as [$label, $url]): ?>
                        <li><a href="<?= esc($url, 'attr') ?>" class="hover:text-black transition"><?= esc($label) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-widest mb-6">Status</h4>
                <p class="text-sm text-gray-500 leading-relaxed"><?= esc($profile['availability'] ?? 'Catatan jaringan, server, dan pengembangan aplikasi.') ?></p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-zinc-100 text-[10px] text-zinc-400 uppercase tracking-widest gap-4">
            <p>&copy; <?= date('Y') ?> <?= esc($siteTitle) ?>. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const hideLoader = (() => {
            let hidden = false;

            return () => {
                if (hidden) return;
                hidden = true;

                const loader = document.getElementById('loader');
                const bar = document.getElementById('loader-bar');

                if (!loader) return;
                if (bar) bar.style.transform = 'scaleX(1)';

                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 700);
                    checkScroll();
                }, 450);
            };
        })();

        document.addEventListener('DOMContentLoaded', () => {
            checkScroll();
            setTimeout(hideLoader, 300);
        });

        window.addEventListener('load', hideLoader);
        setTimeout(hideLoader, 1800);

        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('bg-white/80', 'backdrop-blur-md', 'py-4', 'border-b');
                nav.classList.remove('py-6');
            } else {
                nav.classList.remove('bg-white/80', 'backdrop-blur-md', 'py-4', 'border-b');
                nav.classList.add('py-6');
            }
            checkScroll();
        });

        function checkScroll() {
            document.querySelectorAll('.reveal').forEach(el => {
                const revealTop = el.getBoundingClientRect().top;
                if (revealTop < window.innerHeight - 100) el.classList.add('active');
            });
        }

        function toggleMenu(force) {
            const menu = document.getElementById('mobile-menu');
            const toggle = document.getElementById('mobile-menu-toggle');
            const openIcon = document.querySelector('[data-menu-icon="open"]');
            const closeIcon = document.querySelector('[data-menu-icon="close"]');
            const willOpen = typeof force === 'boolean' ? force : !menu.classList.contains('open');

            menu.classList.toggle('open', willOpen);
            document.body.classList.toggle('overflow-hidden', willOpen);

            if (toggle) {
                toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                toggle.setAttribute('aria-label', willOpen ? 'Close navigation' : 'Open navigation');
            }

            if (openIcon && closeIcon) {
                openIcon.classList.toggle('hidden', willOpen);
                closeIcon.classList.toggle('hidden', !willOpen);
            }
        }

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                toggleMenu(false);
            }
        });
    </script>
</body>

</html>
