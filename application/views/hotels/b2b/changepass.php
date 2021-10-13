<div class="content">
    <div class="container-fluid" style="max-width:1000px">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title text-center"><?=lang('changepass')?></h4>
                        <?php echo $this->session->flashdata('pass_updated');?>
                    </div>
                    <div class="content">
                        <?= form_open('b2b/dashboard/update_pass') ?>
                        <div class="form-group">
                            <label><?=lang('pass')?></label>
                            <input type="password" name="oldpass" placholder="Enter Your Old Password" class="form-control" />
                            <?= form_error('oldpass') ?>
                        </div> 
                        <div class="form-group">
                            <label><?=lang('newpass')?></label>
                            <input type="password" name="pass" placholder="Enter Your Password" class="form-control" />
                            <?= form_error('pass') ?>
                        </div>
                        <div class="form-group">
                            <label><?=lang('conf').' '.lang('pass')?></label>
                            <input type="password" name="confirm_pass" placholder="Confirm Password" class="form-control" />
                            <?= form_error('confirm_pass') ?>
                        </div>
                        <div class="text-center">
                            <input type="submit" class="btn btn-block btn-info" value="Update Password" />
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>