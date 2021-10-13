<div class="container my-5">

    <div class="container mt-3">

        <div class="card p-4">
            <div class="card-header p-3 invoice-head">
                <p><span class="mcolor"><?= lang("resDetails") ?> </span></p>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col">

                        <?= form_open('user/check_invoice',  'class="form reg-form mt-4 p-3"') ?>
                        <div class="row">
                            <div class="col-xl-6 col-md-12">
                                <label for="email"><?= lang('email') ?></label>
                                <input id="email" type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" placeholder="<?= lang('enter') . ' ' . lang('email') ?>" autofocus>
                                <span class="text-danger text-center mt-1">
                                    <?php echo form_error('email'); ?>
                                </span>
                            </div>
                            <div class="col-xl-6 col-md-12">
                                <label><?= lang('resno') ?></label>
                                <input type="text" name="resno" class="form-control" value="<?php echo set_value('resno'); ?>" placeholder="<?= lang('enter') . ' ' . lang('resno') ?>" autofocus>
                                <span class="text-danger text-center">
                                    <?php echo form_error('resno'); ?>
                                </span>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="text-center">
                                    <button type="submit" class="btn pbtn p-2"><i class="fa fa-search"></i> <?= lang('find') ?></button>
                                </div>
                            </div>
                        </div>
                        <?= form_close() ?>

                    </div>
                </div>
            </div>
            <div class="card-footer invoice-footer">
            <p><?=lang('resfind')?> </p>
            </div>
            <!-- invoice -->
        </div>
    </div>
</div>