<div class="container home_container p-md-4 mt-5">
    <div class="row p-md-3 mt-5">
        <div class="col mx-auto mt-5">
            <h3 class="mcolor text-center font-weight-bold"><?= lang('hotels') ?></h3>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="px-md-5 mt-4">
                        <h5 class="font-weight-bold"><i class="fa fa-city"></i> <?= $city; ?></h5>
                        <ul class="list-cols" data-cols="4">
                            <?php foreach ($hotels as $h) : ?>
                                <li><i class="fas fa-hotel"></i> <a style='color:#777' href="<?= base_url('hotel/details/' . $h->hslug) ?>"><?= tolang($h->Hotel_ID, 'hotelname') ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>