<section id="hero" class="login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
                <div id="login" class='rtl_reg'>
                    <div class="text-center"><img src="<?=base_url('public_designs')?>/img/logo_2x.png" width="160"
                            height="34" alt="Image" data-retina="true"></div>
                    <h3 class='text-center'><?=comtrans('signin')?></h3>
                    <hr>
                    <?php echo form_open(); ?>
                    <form>
                        <div class="sign-in-wrapper">
                            <div class="form-group">
                            <?php echo form_error('email', '<p class="alert alert-danger">', '</p>'); ?>
                                <label><?=comtrans('email')?></label>
                                <input type="text" name='email' class=" form-control" placeholder="Enter Email">
                            </div>
                            <div class="form-group">
                            <?php echo form_error('password', '<p class="alert alert-danger">', '</p>'); ?>
                                <label><?=comtrans('pswd')?></label>
                                <input type="password" class=" form-control" name='password'
                                    placeholder="Enter Password">
                            </div>
                            <div class="float-right"><a id="forgot" href="javascript:void(0);"><?=comtrans('f_pass')?></a>
                            </div>
                            <div class="float-left"><a id="forgot" href="<?php echo base_url('pusers/regsiter');?>"><?=comtrans('n_user')?> <i><?=comtrans('r_user')?></i></a>
                            </div>
                           
                            <div id="pass-info" class="clearfix"></div>
                            <button class="btn_full"><?=comtrans('signin')?></button>
                        </div>
                    </form>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</section>