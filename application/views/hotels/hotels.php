<div class="container home_container p-4">
    <div class="row p-md-3 mt-2">
        <div class="col mx-auto">
            <h3 class="mcolor text-center font-weight-bold"><?= lang('hotels') ?></h3>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="px-md-5 mt-4">
                        <h5 class="font-weight-bold"><i class="fa fa-city"></i> <?= lang(isset($city) ? $city : lang('mak')) ?></h5>
                        
                        <ul class="list-cols" data-cols="4">
                            <?php foreach ($makkah as $h) : ?>
                                <li><i class="fas fa-hotel"></i> <a style='color:#777' href="<?= site_url('hotel/details/' . $h->hslug) ?>"><?= tolang($h->Hotel_ID, 'hotelname') ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <span class="font-weight-bold"><?= lang('seemore') ?> <a href="<?= site_url('hotels/city/makkah') ?>" class="clink"><?= lang('mak') ?></a></span>
                    </div>
                    <div class="px-md-5 mt-4">
                        <h5 class="font-weight-bold"><i class="fa fa-city"></i> <?= lang(isset($city)? $city:lang('mad')) ?> </h5>

                        <ul class="list-cols" data-cols="4">
                            <?php foreach ($madinah as $h) : ?>
                                <li><i class="fas fa-hotel"></i> <a style='color:#777' href="<?= site_url('hotel/details/' . $h->hslug) ?>"><?= tolang($h->Hotel_ID, 'hotelname') ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <span class="font-weight-bold"> <?= lang('seemore') ?> <a href="<?= site_url('hotels/city/madinah') ?>" class="clink"><?= lang('mad') ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>