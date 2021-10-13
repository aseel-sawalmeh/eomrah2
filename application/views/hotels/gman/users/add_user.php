<!-- Next User Add Page -->
<div class="content-wrapper">
    <section class="content">
        <?= validation_errors() ?>
        <?= form_open('gman/users/user_add_go') ?>
        <h2 class="text-center p-3"><?=lang('addadmin')?></h2>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6 col-md-12">
                        <div class="form-group">
                            <label><?=lang('fullname')?></label>
                            <input type="text" class="form-control" name="FullName" />
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <label><?=lang('uname')?></label>
                        <input type="text" class="form-control" name="LoginName" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-md-12">
                        <label><?=lang('pass')?></label>
                        <input type="password" class="form-control" name="Password" />
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <label><?=lang('passConf')?></label>
                        <input type="password" class="form-control" name="C_Pass" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-md-12">
                        <div class="form-group">
                            <label><?=lang('em')?></label>
                            <input type="text" class="form-control" name="Email" />
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <div class="form-group">
                            <label><?=lang('phone_number')?></label>
                            <input type="text" class="form-control" name="PhoneNum" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <input type="submit" class="btn btn-block btn-success" value="<?=lang('conf')?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</form>