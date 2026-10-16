<?php if ($labs): ?>
<section id="labs" class="py-24 px-8 md:px-20 bg-[#faf8f2]">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-accent">Infrastruktur Pribadi</span>
            <h2 class="serif text-5xl mt-2">Home Lab</h2>
        </div>
        <a href="<?= site_url('docs/basic-configuration') ?>" class="text-xs font-bold uppercase tracking-widest btn-premium">Basic Configuration &rarr;</a>
    </div>
    <?php foreach ($labs as $lab): ?>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        <a href="<?= site_url('labs/' . $lab['slug']) ?>" class="lg:col-span-7 block border bg-white p-3 md:p-6 hover:shadow-lg transition">
            <img src="<?= esc(portfolio_asset_url($lab['topology_image'] ?: $lab['thumbnail']), 'attr') ?>" alt="Topologi <?= esc($lab['title'], 'attr') ?>" class="w-full h-auto" loading="lazy" decoding="async">
        </a>
        <div class="lg:col-span-5 lg:pt-4">
            <h3 class="serif text-3xl mb-6"><?= esc($lab['title']) ?></h3>
            <p class="text-zinc-600 leading-relaxed"><?= esc($lab['short_description']) ?></p>
            <p class="text-zinc-500 text-sm leading-relaxed mt-5">Saya memakai lab ini untuk belajar VLAN, virtualisasi, DNS, dan pengelolaan server. Website portofolio ini juga berjalan di Debian dalam lab tersebut.</p>
            <div class="mt-8 space-y-4 text-sm border-t pt-6">
                <p><span class="text-accent font-semibold">VLAN 10</span> &nbsp; Management</p>
                <p><span class="text-accent font-semibold">VLAN 20</span> &nbsp; Server dan layanan</p>
                <p><span class="text-accent font-semibold">VLAN 30</span> &nbsp; Client dan guest</p>
            </div>
            <a href="<?= site_url('labs/' . $lab['slug']) ?>" class="inline-block mt-10 bg-black text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-zinc-800 transition">Jelajahi Home Lab &rarr;</a>
        </div>
    </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>
