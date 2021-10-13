<div class="container home_container p-4">

    <div class="row">

        <div class="col-md-12 mb-3 p-0">
           <a href="<?= site_url('blog/detail/') . $f_blogs->b_slug ?>" > <img class="img-fluid w-100" alt="" style="max-height:250px" src="<?= base_url('assets/images/products/') . $f_blogs->Product_Main_Photo ?>"></a>
            <h3 class="text-center mt-2"> 
           <a class="color-black" href="<?= base_url('blog/detail/') . $f_blogs->b_slug ?>" > <?= tolang($f_blogs->P_ID, 'prodtitle') ?></a></h3>
        </div>
      
        
        <div class="col-md-12 mt-2">
            <?php foreach ($blogs as $b) : ?>
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?= site_url('blog/detail/') . $b->b_slug ?>"><img class="card-img-top" src="<?= base_url('assets/images/products/') . $b->Product_Main_Photo ?>"></a>
                    </div>
                    <div class="col-md-8">
                        <h5><a class="color-black" href="<?= site_url('blog/detail/') . $b->b_slug ?>"><?= tolang($b->P_ID, 'prodtitle') ?></a></h5>
                        <p><?= substr(tolang($b->P_ID, 'proddesc'), 100, 200) . '.....' ?></p>
                        <a style="color:white" class="btn-sm btn-warning" href="<?= site_url('blog/detail/') . $b->b_slug ?>" >Read More</a>

                    </div>
                </div>
                <hr>
            <?php endforeach; ?>

        </div>
    </div>

</div>