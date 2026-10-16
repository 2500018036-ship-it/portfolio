<?php
$brandTitle = trim((string) ($siteTitle ?? 'Muhammad Azril Achdi'));
$brandLogo = trim((string) ($siteLogo ?? ''));
$homeUrl = rtrim(site_url('/'), '/');
$anchor = static fn (string $id): string => $homeUrl . '/#' . $id;
$links = [
    ['Beranda', site_url('/')],
    ['Profil', $anchor('about')],
    ['Keahlian', $anchor('skills')],
    ['Proyek', $anchor('projects')],
    ['Home Lab', $anchor('labs')],
    ['Sertifikat', $anchor('certificates')],
    ['Pengalaman', $anchor('experience')],
    ['Pendidikan', $anchor('education')],
    ['Dokumentasi', site_url('docs')],
    ['Blog', site_url('blog')],
    ['Galeri', site_url('gallery')],
    ['Kontak', $anchor('contact')],
];
?>
<style>
    .public-mobile-panel {
        transform: translateX(100%);
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .public-mobile-panel.open {
        transform: translateX(0);
    }
</style>

<nav class="fixed top-0 w-full z-50 py-4 px-6 md:px-12 lg:px-20 flex justify-between items-center gap-6 bg-white/80 backdrop-blur-md border-b">
    <a href="<?= site_url('/') ?>" class="serif text-2xl tracking-tighter font-bold shrink-0 max-w-[58vw] truncate" aria-label="<?= esc($brandTitle, 'attr') ?>">
        <?php if ($brandLogo): ?>
            <img src="<?= esc($brandLogo, 'attr') ?>" alt="<?= esc($brandTitle, 'attr') ?>" class="h-8 w-auto max-w-[180px] object-contain" onerror="this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden')">
            <span class="hidden"><?= esc($brandTitle) ?></span>
        <?php else: ?>
            <?= esc($brandTitle) ?>
        <?php endif; ?>
    </a>

    <div class="hidden xl:flex flex-wrap justify-end gap-x-5 gap-y-2 text-[10px] tracking-widest uppercase font-semibold max-w-[70vw]">
        <?php foreach ($links as [$label, $url]): ?>
            <a href="<?= esc($url, 'attr') ?>" class="btn-premium"><?= esc($label) ?></a>
        <?php endforeach; ?>
    </div>

    <button id="public-mobile-menu-toggle" class="xl:hidden hover:opacity-50 transition" type="button" aria-label="Open navigation" aria-controls="public-mobile-menu" aria-expanded="false">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <path data-public-menu-icon="open" d="M4 8h16M4 16h10" />
            <path data-public-menu-icon="close" class="hidden" d="M6 6l12 12M18 6L6 18" />
        </svg>
    </button>
</nav>

<aside id="public-mobile-menu" class="public-mobile-panel fixed top-0 right-0 h-full w-full max-w-[320px] bg-white z-[90] shadow-2xl p-8 xl:hidden overflow-y-auto">
    <div class="flex items-center justify-between mb-12">
        <span class="serif text-2xl tracking-tighter font-bold truncate pr-6"><?= esc($brandTitle) ?></span>
        <button id="public-mobile-menu-close" class="hover:opacity-50 transition" type="button" aria-label="Close navigation">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
    </div>
    <div class="space-y-6 text-sm uppercase tracking-widest font-semibold">
        <?php foreach ($links as [$label, $url]): ?>
            <a href="<?= esc($url, 'attr') ?>" class="block btn-premium" data-public-menu-link><?= esc($label) ?></a>
        <?php endforeach; ?>
    </div>
</aside>

<script>
    (() => {
        const menu = document.getElementById('public-mobile-menu');
        const toggle = document.getElementById('public-mobile-menu-toggle');
        const close = document.getElementById('public-mobile-menu-close');
        const openIcon = document.querySelector('[data-public-menu-icon="open"]');
        const closeIcon = document.querySelector('[data-public-menu-icon="close"]');

        if (!menu || !toggle) return;

        const setOpen = (open) => {
            menu.classList.toggle('open', open);
            document.body.classList.toggle('overflow-hidden', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
            openIcon?.classList.toggle('hidden', open);
            closeIcon?.classList.toggle('hidden', !open);
        };

        toggle.addEventListener('click', () => setOpen(!menu.classList.contains('open')));
        close?.addEventListener('click', () => setOpen(false));

        document.querySelectorAll('[data-public-menu-link]').forEach((link) => {
            link.addEventListener('click', () => setOpen(false));
        });

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') setOpen(false);
        });
    })();
</script>
