<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <url>
        <loc><?= base_url('en') ?></loc>
        <xhtml:link rel="alternate" hreflang="ar" href="<?= base_url('ar') ?>" />
        <lastmod>2021-04-10</lastmod>
    </url>
    <url>
        <loc><?= base_url('en/home/about') ?></loc>
        <xhtml:link rel="alternate" hreflang="ar" href="<?= base_url('ar' . '/home/about') ?>" />
        <lastmod>2021-04-10</lastmod>
    </url>
    <url>
        <loc><?= base_url('en/home/terms') ?></loc>
        <xhtml:link rel="alternate" hreflang="ar" href="<?= base_url('ar' . '/home/terms') ?>" />
        <lastmod>2021-04-10</lastmod>
    </url>
    <url>
        <loc><?= base_url('en/faq') ?></loc>
        <xhtml:link rel="alternate" hreflang="ar" href="<?= base_url('ar' . '/faq') ?>" />
        <lastmod>2021-04-10</lastmod>
    </url>
    <url>
        <loc><?= base_url('en/user/register') ?></loc>
        <xhtml:link rel="alternate" hreflang="ar" href="<?= base_url('ar' . '/user/register') ?>" />
        <lastmod>2021-04-10</lastmod>
    </url>
    <url>
        <loc><?= base_url('en/hotel_registration') ?></loc>
        <xhtml:link rel="alternate" hreflang="ar" href="<?= base_url('ar' . '/hotel_registration') ?>" />
        <lastmod>2021-04-10</lastmod>
    </url>
    <?php foreach ($hotels as $hotel) : ?>
        <url>
            <loc><?php echo base_url('en') . "/hotel/details/" . $hotel->hslug ?></loc>
            <xhtml:link rel="alternate" hreflang="ar" href="<?= base_url('ar' . "/hotel/details/" . $hotel->hslug) ?>" />
            <priority>0.5</priority>
            <changefreq>weekly</changefreq>
        </url>
    <?php endforeach; ?>
</urlset>