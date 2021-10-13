<footer class="container-fluid p-md-5 footer" style="color:white;width:100%;background-image:url('/assets/images/footerbg.png');background-size:cover;">
    <div class="row page-footer font-small pt-md-4 pt-3">
        <div class="container">
            <div class="row ">
                <div class="col-sm col-md-3">
                    <a class="scup d-md-none d-block" onclick="window.scrollTo(0, 0);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                            <defs>
                                <style>
                                    .a {
                                        fill: #fff;
                                    }
                                </style>
                            </defs>
                            <path class="a" d="M10.378,21.185a1.362,1.362,0,0,1,0-1.92l6.729-6.708a1.356,1.356,0,0,1,1.87-.042l6.63,6.609a1.355,1.355,0,1,1-1.912,1.92L18,15.434l-5.7,5.759A1.356,1.356,0,0,1,10.378,21.185Z" transform="translate(-3.375 -3.375)" />
                            <path class="a" d="M18,32.625A14.625,14.625,0,1,0,3.375,18,14.623,14.623,0,0,0,18,32.625Zm0-2.25A12.37,12.37,0,0,1,9.253,9.253,12.37,12.37,0,1,1,26.747,26.747,12.269,12.269,0,0,1,18,30.375Z" transform="translate(-3.375 -3.375)" />
                        </svg>
                    </a>
                    <img src="<?= banner(100) ?>" class="img-fluid" alt="">

                    <ul class="list-unstyled mt-2">
                        <li>
                            <a class="flink" href="<?= site_url('home/about') ?>"><?= lang('aboutus') ?></a>
                        </li>
                        <li>
                            <a class="flink" href="<?= site_url('home/terms') ?>"><?= lang('terms') ?></a>
                        </li>
                        <li>
                            <a class="flink" href="<?= site_url('faq') ?>"><?= lang('faqs') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm col-md-4 d-none d-md-block text-center">
                    <!-- <p class="mt-2 regulations"><?= lang('undertheregulations') ?></p> -->
                    <p class="mt-2 regulations"> Under the regulations of</p>
                    <p class="regulation"> Ministry of Hajj and Umrah GDS System</p>
                    <img src="<?= banner(101) ?>" class="img-fluid" alt="" />
                    <img src="<?= banner(103) ?>" class="img-fluid" alt="" />
                </div>
                <div class="col-sm col-md-5">
                    <a class="scup d-none d-md-block" onclick="window.scrollTo(0, 0);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="29.25" height="29.25" viewBox="0 0 29.25 29.25">
                            <defs>
                                <style>
                                    .a {
                                        fill: #fff;
                                    }
                                </style>
                            </defs>
                            <path class="a" d="M10.378,21.185a1.362,1.362,0,0,1,0-1.92l6.729-6.708a1.356,1.356,0,0,1,1.87-.042l6.63,6.609a1.355,1.355,0,1,1-1.912,1.92L18,15.434l-5.7,5.759A1.356,1.356,0,0,1,10.378,21.185Z" transform="translate(-3.375 -3.375)" />
                            <path class="a" d="M18,32.625A14.625,14.625,0,1,0,3.375,18,14.623,14.623,0,0,0,18,32.625Zm0-2.25A12.37,12.37,0,0,1,9.253,9.253,12.37,12.37,0,1,1,26.747,26.747,12.269,12.269,0,0,1,18,30.375Z" transform="translate(-3.375 -3.375)" />
                        </svg>
                    </a>
                    <p><?= lang('subscribe') ?></p>
                    <div class="mt-3">
                        <input type="text">
                        <button class="btn btn-sm cbtn font-weight-bold"><?= lang('send') ?></button>
                    </div>
                    <!-- Social buttons -->
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item">
                            <a class=" mx-1" href="https://twitter.com/eomrahofficial">
                                <i class="fab fa-twitter fa-lg  social_icons"> </i>
                            </a>
                        </li>
                        <!-- <li class="list-inline-item">
                            <a class="mx-1">
                                <i class="fab fa-linkedin-in fa-lg social_icons"> </i>
                            </a>
                        </li> -->
                        <li class="list-inline-item">
                            <a class="mx-1" href="https://www.facebook.com/eomrahofficial">
                                <i class="fab fa-facebook-f fa-lg social_icons"> </i>
                            </a>
                        </li>

                        <!-- <li class="list-inline-item">
                            <a class="social_icons mx-1">
                                <i class="fab fa-google-plus-g social_icons"> </i>
                            </a>
                        </li> -->

                        <li class="list-inline-item">
                            <a class="mx-1" href="https://instagram.com/eomrahofficial">
                                <i class="fab fa-instagram fa-lg social_icons"> </i>
                            </a>
                        </li>
                    </ul>
                    <!-- Social buttons -->
                </div>
            </div>
            <div class="row mt-3 py-3">
                <div class="col text-center copyrights">
                    © 2020 Copyright:
                    <a class="clink" href="https://travelinksa.com">TravelInKsa</a>
                </div>
            </div>
            <!-- Grid row -->
        </div>
    </div>
    <!-- Footer -->


    <div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="text-center">
                        <?php if (lngdir() == "rtl") : ?>
                            <img src="<?= base_url('public_designs/assets/img/logo-ar.png') ?>" class="img-fluid" width="150px" height="20px" alt="">
                        <?php else : ?>
                            <img src="<?= base_url('public_designs/assets/img/logo.png') ?>" class="img-fluid" width="150px" height="20px" alt="">
                        <?php endif; ?>
                    </div>
                    <h5 class="modal-title text-center" id="exampleModalLongTitle">Sign In</h5>
                </div>
                <h6 id="ForMsg" class="text-center"></h6>
                <div class="modal-body">
                    <?php echo form_open(site_url('plogin/booklogin')); ?>
                    <div class="sign-in-wrapper">
                        <label class="color-black"><?= lang('em') ?></label>
                        <div class="md-form">

                            <input id="uemail" type="email" name='email' class=" form-control" placeholder="<?= lang('emailoruser') ?>">
                        </div>
                        <label class="color-black"><?= lang('pass') ?></label>
                        <div class="md-form">

                            <input id="upassword" type="password" class=" form-control" name='password' placeholder="<?= lang('enter') . ' ' . lang('pass') ?>">
                        </div>
                        <div id="pass-info" class="clearfix"></div>
                        <div class="text-center">
                            <button type="button" class="btn btn-info btn-block" onclick="ulogin()"><?= lang('login') ?>
                            </button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="text-center mt-3">
                        <p class="color-black"><?= lang('notamember') ?><a href="<?= site_url('user/register') ?>"> <?= lang('u_reg') ?></a></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('close') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- UserLogin -->
    <div class="modal fade" id="signin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content signinmodal">
                <div class="modal-header ">
                    <h3>USERLOG<span class="mcolor">IN</span></h3>
                    <div class="logo">
                        <?php if (lngdir() == "rtl") : ?>
                            <img src="<?= base_url('public_designs/assets/img/logo-ar.png') ?>" class="img-fluid" width="150px" height="20px" alt="">
                        <?php else : ?>
                            <img src="<?= base_url('public_designs/assets/img/logo.png') ?>" class="img-fluid" width="150px" height="20px" alt="">
                        <?php endif; ?>
                    </div>

                </div>
                <div class="modal-body p-5">
                    <h6 id="gForMsg" class="text-center"></h6>
                    <?php echo form_open('plogin/booklogin', ['id' => 'gsignin']); ?>
                    <div class="sign-in-wrapper">
                        <label for="guemail" class="loginlabel"><?= lang('em') ?></label>
                        <input class="form-control mb-5" id="guemail" type="email" name='email' placeholder="<?= lang('emailoruser') ?>" autocomplete="off">
                        <label for="gupassword" class="loginlabel"><?= lang('pass') ?></label>
                        <input id="gupassword" type="password" class="form-control" name="password" placeholder="<?= lang('enter') . ' ' . lang('pass') ?>" autocomplete="off">
                    </div>
                    <?php echo form_close(); ?>
                    <div class="text-center mt-3">
                        <button type="button" class="btn ebtn px-5" onclick="gulogin()">
                            <?php echo lang('login'); ?>
                        </button>
                        <p class="p-2 m-2"><?= lang('notamember') ?> <a class="clink" href="<?= site_url('user/register') ?>">
                                <?= lang('u_reg') ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /UserLogin -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->

    <script src="<?php echo base_url('public_designs/assets') ?>/js/popper.js" defer></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/bootstrap-4.4.1.js" defer></script>
    <script>
        var base_url = '<?= site_url() ?>';
    </script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/custom.js" defer></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/slick.min.js"></script>

    <script>
        var imgSliders = document.getElementsByClassName('image_slider');
        var htSliders = document.getElementsByClassName('hotel_slider');
        if (imgSliders.length > 0 || htSliders.length > 0) {
            $('.image_slider').slick({
                centerMode: true,
                centerPadding: '0px',
                slidesToShow: 4,
                autoplay: true,
                autoplaySpeed: 5000,
                <?php if (lngdir() == "rtl") : ?>
                    rtl: true,
                <?php else : ?>
                    rtl: false,

                <?php endif; ?>
                responsive: [{
                        breakpoint: 768,
                        settings: {
                            arrows: true,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            arrows: false,
                            centerMode: true,
                            centerPadding: '40px',
                            slidesToShow: 1
                        }
                    }
                ],
            });
        }
    </script>
    <script>
        window.scroll({
            top: 0,
            left: 0,
            behavior: 'smooth'
        });
    </script>

</footer>
</body>

</html>