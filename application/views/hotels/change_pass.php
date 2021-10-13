<div class="container home_container bg-white">
    <div class="row">
        <div class="col-md-12  border">
                <?=form_open('user/changepassword')?>
                <div class="form-group">
                    <label><?=lang('pass')?></label>
                    <input class="form-control" type="password" name="Pass">
                   <?= form_error('Pass') ?>
                </div>
                <div class="form-group">
                    <label><?=lang('newpass')?></label>
                    <input class="form-control" type="password" name="password">
                    <?= form_error('password') ?>
                </div>
                <div class="form-group">
                    <label><?=lang('passConf')?></label>
                    <input class="form-control" type="password" name="newpass">
                  <?= form_error('newpass') ?>
                </div>
                <div class="text-center mb-3">
                    <input type="submit" class="btn btn-warning">
                </div>
           <?=form_close()?>
        </div>
    </div>
</div>