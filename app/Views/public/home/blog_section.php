<?php if ($blogPosts): ?>
<section id="blog" class="py-24 px-8 md:px-20">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-accent">Catatan Teknis</span>
            <h2 class="serif text-5xl mt-2">Dari Home Lab ke Blog</h2>
        </div>
        <a href="<?= site_url('blog') ?>" class="text-xs font-bold uppercase tracking-widest btn-premium">Semua Artikel &rarr;</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach (array_slice($blogPosts, 0, 3) as $post): ?>
        <a href="<?= site_url('blog/' . $post['slug']) ?>" class="group border block bg-white hover:shadow-lg transition">
            <div class="aspect-[4/3] bg-white border-b p-4 flex items-center justify-center">
                <img src="<?= esc(portfolio_asset_url($post['thumbnail']), 'attr') ?>" alt="<?= esc($post['title'], 'attr') ?>" class="max-h-full max-w-full object-contain" loading="lazy" decoding="async">
            </div>
            <div class="p-6">
                <p class="text-[10px] uppercase tracking-widest text-accent"><?= esc($post['category_name']) ?></p>
                <h3 class="serif text-2xl mt-3 mb-4 group-hover:text-accent transition"><?= esc($post['title']) ?></h3>
                <p class="text-sm text-zinc-500 leading-relaxed"><?= esc($post['excerpt']) ?></p>
                <span class="inline-block text-xs font-bold uppercase tracking-widest mt-6">Baca Artikel &rarr;</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
