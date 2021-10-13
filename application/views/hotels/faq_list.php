<div class="container home_container">
    <div class="row p-4">
        <div class="col-12">
            <img src="<?= banner(107) ?>" class="img-fluid rounded" alt="">
        </div>
    </div>
    <div class="row p-4">
        <div class="col-md-2 mt-3">
            <?php foreach ($category as $cat) : ?>
                <ul class="list-group">
                    <li class="list-group-item text-center"><a style="color:black" href="<?= site_url('faq/category/') . $cat->cat_name ?>"><?= lang($cat->cat_name) ?></a></li>
                </ul>
            <?php endforeach; ?>
        </div>
        <div class="col-md-10 mt-3">
            <div class="accordion" id="accordionExample">
                <?php if ($faqs) : ?>
                    <?php foreach ($faqs as $f) : ?>
                        <div class="card">
                            <div class="" id="headingOne">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#<?= $f->q_slug ?>" aria-expanded="false" aria-controls="collapseOne">
                                    <h5 class="mb-0" style="color:black">
                                        <!--<i class="fa fa-angle-double-down text-right" style="font-size:20px"></i>-->
                                        <?= tolang($f->id ,'qsfaq') ?>
                                    </h5>
                                </button>
                            </div>
                            <div id="<?= $f->q_slug ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <?=tolang($f->id , 'ansfaq') ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <h4 class="text-center">No Faqs</h4>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>