<div class="content-wrapper">
    <section class="content mt-2">
        <div class="container-fluid bg-white p-2">
            <div class="row">
                <div class="col-sm-6">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text black text-bold"><?=lang('activebloglist')?></p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $in_pro; ?></h1>
                            <a href="<?php echo base_url('gman/products/pending'); ?>" class="small-box-footer">More
                                info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text black text-bold"><?=lang('inactivebloglist')?></p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $ac_pro; ?></h1>
                            <a href="<?php echo base_url('gman/products/active'); ?>" class="small-box-footer m-color">More info
                                <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
              

                <!-- <div class="col-sm-4">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text black text-bold">New Hotels Providing Requests</p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $providing_requests; ?></h1>
                            <a href="<?php echo base_url('gman/providers/requested_hotel'); ?>"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>

                    </div>
                </div> -->



            </div>
            <!-- <div class="row">
                <div class="col-sm-4">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text black text-bold">Active Providers</p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $providers_count; ?></h1>
                            <a href="<?php echo base_url('gman/providers/active_list'); ?>"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text black text-bold">Active System Users</p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $active_user; ?></h1>
                            <a href="<?php echo base_url('gman/hotel_system/husers/active'); ?>"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-number black text-bold">Pending System Users </p>
                            <h1 class="info-box-text m-color text-bold">  <?php echo $inactive_user; ?></h1>
                            <a href="<?php echo base_url('gman/hotel_system/husers/inactive'); ?>"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

            </div> -->






            <div class="row">
              

                <div class="col-12">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text black text-bold"><?=lang('hotels')?></p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $h_counts; ?></h1>
                        </div>
                    </div>
                </div>


            </div>


<!-- 
            <div class="row">


                <div class="col-12">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text black text-bold">Existing Hotel Providing Request</p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $ex_re; ?></h1>
                            <a href="<?php echo base_url('gman/providers/pending_list'); ?>"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

            </div> -->



        </div>
    </section>
</div>