<div class="container-fluid page_banner m-0 p-0">

</div>
<div class="container home_container hotel-reg mt-3 p-4">
    <div class="row m-1 reg-head p-3">
        <div class="col col-md-4 pt-2">
            <i class="fas fa-hotel mx-3" style="color:#ffff; font-size:4vw; position:relative">
                <i class="fa fa-hotel"
                    style="color:#e6a423; font-size:2vw; position:absolute; right: -1vw;bottom: -0.5vw"></i>
            </i>
            <i class="fa fa-utensils" style="color:#e6a423; font-size:2vw;"></i>
        </div>
        <div class="col col-md-8 head-title justify-content-center">
            <h4 class="m-auto">List Your <span class="mcolor">Property</span></h4>
        </div>
    </div>
    <div class="row p-1">
        <div class="col">
            <?= form_open('hotel_registration',  'class="form reg-form mt-4 p-3"') ?>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <label for="fullname"><?= lang('fullname') ?></label>
                    <input id="fullname" type="text" name="FullName" class="form-control"
                        value="<?php echo set_value('FullName'); ?>"
                        placeholder="<?= lang('enter') . ' ' . lang('fullname') ?>" autofocus>
                    <span class="text-danger text-center mt-1">
                        <?php echo form_error('FullName'); ?>
                    </span>
                </div>
                <div class="col-xl-6 col-md-12">
                    <label><?= lang('uname') ?></label>
                    <input type="text" name="UserName" class="form-control" value="<?php echo set_value('UserName'); ?>"
                        placeholder="<?= lang('enter') . ' ' . lang('uname') ?>" autofocus>
                    <span class="text-danger text-center">
                        <?php echo form_error('UserName'); ?>
                    </span>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-xl-6 col-md-12">
                    <label><?= lang('em') ?></label>
                    <input type="text" name="email" class="form-control" value="<?php echo set_value('Email'); ?>"
                        placeholder="<?= lang('enter') . ' ' . lang('em') ?>" autofocus>
                    <span class="text-danger text-center">
                        <?php echo form_error('Email'); ?>
                    </span>
                </div>
                <div class="col-xl-6 col-md-12">
                    <label>confirm<?= lang('em') ?></label>
                    <input type="text" name="confirmemail" class="form-control"
                        value="<?php echo set_value('Email'); ?>" placeholder="<?= lang('enter') . ' ' . lang('em') ?>"
                        autofocus>
                    <span class="text-danger text-center">
                        <?php echo form_error('Email'); ?>
                    </span>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-xl-6 col-md-12">
                    <label><?= lang('contact') ?></label>
                    <input type="text" name="Phone" value="<?php echo set_value('Phone'); ?>" class="form-control"
                        placeholder="<?= lang('enter') . ' ' . lang('contact') ?>" autofocus>
                    <span class="text-danger text-center">
                        <?php echo form_error('Phone'); ?>
                    </span>
                </div>
                <div class="col-xl-6 col-md-12">
                    <label><?= lang('bt') ?></label>

                    <select name="businesstype" class="form-control">
                        <option value="1" selected>Hotel Owner</option>
                    </select>
                    <span class="text-danger text-center">
                        <?php echo form_error('type'); ?>
                    </span>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-xl-6 col-md-12">
                    <label><?= lang('pass') ?></label>
                    <input type="password" name="Password" class="form-control" id="password"
                        placeholder="<?= lang('enter') . ' ' . lang('pass') ?>">
                    <span class="text-danger text-center">
                        <?php echo form_error('Password'); ?>
                    </span>
                </div>
                <div class="col-xl-6 col-md-12">
                    <label><?= lang('confirm') . ' ' . lang('pass') ?></label>

                    <input type="password" name="Passwordconfirm" class="form-control" id="password"
                        placeholder="<?= lang('confirm') . ' ' . lang('pass') ?>">
                    <span class="text-danger text-center">
                        <?php echo form_error('Passwordconfirm'); ?>
                    </span>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="text-center">
                        <button type="submit" class="btn pbtn p-2"><i class="fa fa-user-plus"></i>
                            <?= lang('reg') ?></button>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>