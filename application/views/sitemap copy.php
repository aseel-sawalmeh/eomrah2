<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <url>
        <loc><?=base_url('en')?></loc>
        <?php foreach (langs() as $lang): ?>
        <xhtml:link rel="alternate" hreflang="<?=$lang->code?>" href="<?=base_url($lang->code)?>" />
        <?php endforeach;?>
    </url>
    <url>
        <loc><?=base_url('en/home/about')?></loc>
        <?php foreach (langs() as $lang): ?>
        <xhtml:link rel="alternate" hreflang="<?=$lang->code?>" href="<?=base_url($lang->code . '/home/about')?>" />
        <?php endforeach;?>
    </url>
    <url>
        <loc><?=base_url('en/home/terms')?></loc>
        <?php foreach (langs() as $lang): ?>
        <xhtml:link rel="alternate" hreflang="<?=$lang->code?>" href="<?=base_url($lang->code . '/home/terms')?>" />
        <?php endforeach;?>
    </url>
    <url>
        <loc><?=base_url('en/faq')?></loc>
        <?php foreach (langs() as $lang): ?>
        <xhtml:link rel="alternate" hreflang="<?=$lang->code?>" href="<?=base_url($lang->code . '/faq')?>" />
        <?php endforeach;?>
    </url>
    <url>
        <loc><?=base_url('en/user/register')?></loc>
        <?php foreach (langs() as $lang): ?>
        <xhtml:link rel="alternate" hreflang="<?=$lang->code?>" href="<?=base_url($lang->code . '/user/register')?>" />
        <?php endforeach;?>
    </url>
    <url>
        <loc><?=base_url('en/hotel_registration')?></loc>
        <?php foreach (langs() as $lang): ?>
        <xhtml:link rel="alternate" hreflang="<?=$lang->code?>" href="<?=base_url($lang->code . '/hotel_registration')?>" />
        <?php endforeach;?>
    </url>
    <?php foreach ($hotels as $hotel): ?>
    <url>
        <loc><?php echo base_url('en') . "/hotel/details/" . $hotel->hslug ?></loc>
        <?php foreach (langs() as $lang): ?>
        <xhtml:link rel="alternate" hreflang="<?=$lang->code?>" href="<?=base_url($lang->code . "/hotel/details/" . $hotel->hslug)?>" />
        <?php endforeach;?>
        <priority>0.5</priority>
        <changefreq>daily</changefreq>
    </url>
        <?php endforeach;?>
</urlset>