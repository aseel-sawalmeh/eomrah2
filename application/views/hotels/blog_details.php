<div class="home_container container bg-white p-4">
    <div class="row">
        <div class="col">
            <img class="img-fluid rounded" alt="" height="282" src="<?= base_url('assets/images/products/') . $blog_detail->Product_Main_Photo ?>" style="max-height:260px; width:100%" />
        </div>
    </div>
    <div class="row">

        <div class="col-12 blog-content">
            <h6 class="text-left my-4 font-weight-bold mcolor"> <?= tolang($blog_detail->P_ID, 'prodtitle') ?></h6>

            <p class="mt-3  mt-2"><?= tolang($blog_detail->P_ID, 'proddesc') ?></p>

        </div>
    </div>

    <h3 class="text-center mt-2"><b><?= lang('relatedtopics') ?></b></h3>
    <div class="row image_slider mt-5"">
<?php foreach ($blogs as $b) : ?>
    <div class=" col">
        <a href="<?= base_url('blog/detail/') . $b->b_slug ?>"><img style="width:100%;height:200px;" class="img-fluid rounded" src="<?= base_url('assets/images/products/') . $b->Product_Main_Photo ?>"></a>
        <a class="clink" href="<?= base_url('blog/detail/') . $b->b_slug ?>"><?= tolang($b->P_ID, 'prodtitle') ?></a>
    </div>
<?php endforeach; ?>
</div>
</div>